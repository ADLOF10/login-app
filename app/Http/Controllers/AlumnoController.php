<?php

namespace App\Http\Controllers;


use App\Models\Alumno;
use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\QrCode;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Grupo;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AlumnoController extends Controller
{

    public function create()
    {
        $userId = Auth::id(); // ID del profesor autenticado

        // Filtrar grupos del profesor autenticado
        $grupos = Grupo::whereHas('materia', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        return view('alumnos.create', compact('grupos'));
    }



    public function search(Request $request)
    {
        $request->validate([
            'correo_institucional' => 'required|email|exists:alumnos,correo_institucional',
            'numero_cuenta' => 'required|string|exists:alumnos,numero_cuenta',
        ]);
    
        $alumno = Alumno::where('correo_institucional', $request->correo_institucional)
                        ->where('numero_cuenta', $request->numero_cuenta)
                        ->first();
    
        if (!$alumno) {
            return back()->withErrors(['error' => 'Alumno no encontrado.']);
        }
    
        return response()->json($alumno);
    }

    // public function add(Request $request)
    // {
    //     $request->validate([
    //         'grupo_id' => 'required|exists:grupos,id',
    //         'alumno_id' => 'required|exists:alumnos,id',
    //     ]);

    //     $grupo = Grupo::findOrFail($request->grupo_id);
    //     $grupo->alumnos()->attach($request->alumno_id);

    //     return redirect()->route('grupos.index')->with('success', 'Alumno agregado al grupo exitosamente.');
    // }

    public function add(Request $request)
    {
        Log::info('Datos recibidos en el formulario:', $request->all());

        $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
            'grupo_id' => 'required|exists:grupos,id',
        ]);

        $grupo = Grupo::findOrFail($request->grupo_id);

        if ($grupo->alumnos()->where('alumnos.id', $request->alumno_id)->exists()) {
            return back()->withErrors(['error' => 'El alumno ya está asignado a este grupo.']);
        }

        $grupo->alumnos()->attach($request->alumno_id);

        return redirect()->route('grupos.show', $grupo->id)->with('success', 'Alumno agregado al grupo exitosamente.');
    }



    public function index()
{
    $userId = Auth::id(); // Obtener el ID del profesor autenticado

    // Obtener los alumnos relacionados con los grupos del profesor autenticado o registrados directamente por él
    $alumnos = Alumno::where(function ($query) use ($userId) {
        $query->whereHas('grupos.materia', function ($subQuery) use ($userId) {
            $subQuery->where('user_id', $userId); // Relación con grupos/materias
        })->orWhere('user_id', $userId); // Alumnos registrados directamente por el profesor
    })->with('asistenciasTotales')->get();

    $totalAsistencias = 0;
    $totalRetardos = 0;
    $totalInasistencias = 0;

    foreach ($alumnos as $alumno) {
        $totalAsistencias += $alumno->asistenciasTotales()->where('tipo', 'asistencia')->count();
        $totalRetardos += $alumno->asistenciasTotales()->where('tipo', 'retardo')->count();
        $totalInasistencias += $alumno->asistenciasTotales()->where('tipo', 'inasistencia')->count();
    }

    $datosGraficaDona = [
        'asistencias' => $totalAsistencias,
        'retardos' => $totalRetardos,
        'inasistencias' => $totalInasistencias,
    ];

    $datosGraficaBarras = $alumnos->map(function ($alumno) {
        return [
            'nombre' => $alumno->nombre . ' ' . $alumno->apellidos,
            'porcentaje' => $alumno->calcularPorcentajeAsistencia(),
        ];
    });

    return view('alumnos.index', compact('alumnos', 'datosGraficaDona', 'datosGraficaBarras'));
}




    public function subirAlumnosCSV(Request $request)
    {
        $request->validate([
            'archivo_csv' => 'required|mimes:csv,txt,xlsx|max:2048',
        ]);

        $file = $request->file('archivo_csv');
        $path = $file->getRealPath();

        $data = [];
        $headers = [];

       
        $extension = $file->getClientOriginalExtension();
        if ($extension === 'csv') {
            
            $rows = array_map('str_getcsv', file($path));
            $headers = array_shift($rows); 
            $data = $rows;
        } elseif ($extension === 'xlsx') {
           
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $headers = array_shift($rows); 
            $data = $rows;
        }

        
        $expectedHeaders = ['Nombre', 'Apellidos', 'Correo Institucional', 'Número de Cuenta', 'Semestre'];
        if ($headers !== $expectedHeaders) {
            return back()->with('error', 'La estructura del archivo no es correcta.');
        }

        
        $errores = [];
        foreach ($data as $index => $row) {
            [$nombre, $apellidos, $correo, $numero_cuenta, $semestre] = $row;

            $validator = Validator::make(
                [
                    'nombre' => $nombre,
                    'apellidos' => $apellidos,
                    'correo_institucional' => $correo,
                    'numero_cuenta' => $numero_cuenta,
                    'semestre' => $semestre,
                ],
                [
                    'nombre' => 'required|string|max:255',
                    'apellidos' => 'required|string|max:255',
                    'correo_institucional' => 'required|email|regex:/@alumno\.uaemex\.wip$/|unique:alumnos,correo_institucional|unique:users,email',
                    'numero_cuenta' => 'required|regex:/^\d{7}$/|unique:alumnos,numero_cuenta',
                    'semestre' => 'nullable|string|max:10',
                ]
            );

            if ($validator->fails()) {
                $errores[] = "Error en la fila " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                continue;
            }

            
            $alumno = Alumno::create([
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'correo_institucional' => $correo,
                'numero_cuenta' => $numero_cuenta,
                'semestre' => $semestre,
                'foto_perfil' => 'fotos_perfil/default.png', 
            ]);

            
            User::create([
                'name' => $alumno->nombre . ' ' . $alumno->apellidos,
                'email' => $alumno->correo_institucional,
                'password' => bcrypt($numero_cuenta), 
                'role' => 'alumno',
                'alumno_id' => $alumno->id, 
            ]);
        }

        
        if (count($errores) > 0) {
            return back()->with('error', implode('<br>', $errores));
        }

        return back()->with('success', 'Alumnos registrados correctamente.');
    }



    public function descargarPlantilla()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        
        $sheet->setCellValue('A1', 'Nombre');
        $sheet->setCellValue('B1', 'Apellidos');
        $sheet->setCellValue('C1', 'Correo Institucional');
        $sheet->setCellValue('D1', 'Número de Cuenta');
        $sheet->setCellValue('E1', 'Semestre');
    
       
        $sheet->setCellValue('A2', 'Ejemplo Nombre');
        $sheet->setCellValue('B2', 'Ejemplo Apellidos');
        $sheet->setCellValue('C2', 'ejemplo@alumno.uaemex.wip');
        $sheet->setCellValue('D2', '12345678');
        $sheet->setCellValue('E2', '8vo');
    
        
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
    
        
        $filename = 'Plantilla_Alumnos.xlsx';
        $writer = new Xlsx($spreadsheet);
    
        
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);
    
        
        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }

    public function updateFotoPerfil(Request $request, Alumno $alumno)
    {
        try {
            $request->validate([
                'foto_perfil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('foto_perfil')) {
                $fotoPerfil = $request->file('foto_perfil')->store('fotos_perfil', 'public');
                $alumno->foto_perfil = $fotoPerfil;
                $alumno->save();
            }

            return response()->json([
                'message' => 'Foto de perfil actualizada con éxito',
                'foto_perfil' => asset('storage/' . $alumno->foto_perfil),
            ]);
        } catch (\Exception $e) {
            Log::error("Error al actualizar la foto de perfil: " . $e->getMessage());
            return response()->json(['error' => 'No se pudo actualizar la foto de perfil'], 500);
        }
    }

    public function getAsistenciaChartData(Request $request)
    {
        $user = $request->user();
        $alumno = $user->alumno;
    
        if (!$alumno) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }
    
        
        $datosGrafica = [
            'nombre' => $alumno->nombre . ' ' . $alumno->apellidos,
            'porcentajeAsistencia' => $alumno->calcularPorcentajeAsistencia(),
            'asistencias' => $alumno->asistenciasTotales->count(),
            'clasesTotales' => QrCode::whereIn('grupo_id', $alumno->grupos->pluck('id'))->count(),
        ];
    
        return response()->json($datosGrafica);
    }
    

    public function store(Request $request) 
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'apellidos' => 'required|string|max:255',
        'correo_institucional' => [
            'required',
            'email',
            'regex:/^[a-zA-Z0-9._%+-]+@alumno\.uaemex\.wip$/',
            'unique:alumnos,correo_institucional',
            'unique:users,email',
        ],
        'numero_cuenta' => 'required|digits:7|unique:alumnos,numero_cuenta',
        'semestre' => [
            'nullable',
            'string',
            'max:10',
            'regex:/^[a-zA-Z0-9\s]+$/', // Permite letras, números y espacios solamente
        ],
    ], [
        'correo_institucional.regex' => 'El correo institucional debe tener el formato usuario@alumno.uaemex.wip.',
        'semestre.regex' => 'El campo semestre no puede contener caracteres especiales.',
    ]);

    $profesorId = Auth::id(); // Obtener el ID del profesor autenticado

    // Crear el registro en la tabla alumnos
    $alumno = Alumno::create([
        'nombre' => $request->nombre,
        'apellidos' => $request->apellidos,
        'correo_institucional' => $request->correo_institucional,
        'numero_cuenta' => $request->numero_cuenta,
        'semestre' => $request->semestre,
        'user_id' => $profesorId, // Vincular al profesor autenticado
    ]);

    // Crear el registro en la tabla users con la contraseña por defecto
    User::create([
        'name' => $request->nombre . ' ' . $request->apellidos,
        'email' => $request->correo_institucional,
        'password' => bcrypt('Wip1234$'), // Contraseña predeterminada encriptada
        'role' => 'alumno',
        'alumno_id' => $alumno->id,
    ]);

    return redirect()->route('alumnos.index')->with('success', 'Alumno registrado exitosamente con la contraseña predeterminada.');
}




    



    public function getDetalles(Request $request)
    {
        $user = $request->user();
        $alumno = $user->alumno;
    
        if (!$alumno) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }
    
        return response()->json([
            'id' => $alumno->id, 
            'nombre' => $alumno->nombre,
            'apellidos' => $alumno->apellidos,
            'correo_institucional' => $alumno->correo_institucional,
            'numero_cuenta' => $alumno->numero_cuenta,
            'semestre' => $alumno->semestre,
            'foto_perfil' => $alumno->foto_perfil 
                ? asset('storage/' . $alumno->foto_perfil)
                : asset('storage/fotos_perfil/default.png'), 
        ]);
    }

    public function edit(Alumno $alumno)
    {
        return view('alumnos.edit', compact('alumno')); 
    }

    public function update(Request $request, Alumno $alumno)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo_institucional' => 'required|email|unique:alumnos,correo_institucional,' . $alumno->id,
            'numero_cuenta' => 'required|numeric|unique:alumnos,numero_cuenta,' . $alumno->id,
            'semestre' => 'nullable|string|max:10',
        ]);

        
        $numeroCuentaAnterior = $alumno->numero_cuenta;
        $numeroCuentaNuevo = $request->numero_cuenta;

        if ($numeroCuentaAnterior !== $numeroCuentaNuevo) {
            
            $usuario = $alumno->user;
            if ($usuario) {
                $usuario->password = bcrypt($numeroCuentaNuevo);
                $usuario->save();
            }
        }

       
        $alumno->update($request->all());

        return redirect()->route('alumnos.index')->with('success', 'Alumno y contraseña actualizados exitosamente.');
    }

    public function show(Alumno $alumno)
    {
        $alumno->load('asistenciasTotales.grupo', 'grupos.materia');

        $porcentajeAsistencia = $alumno->calcularPorcentajeAsistencia();
        $datosGrafica = $alumno->asistenciasTotales->groupBy('tipo')->map(function ($asistencias) {
            return $asistencias->count();
        });

        return view('alumnos.show', compact('alumno', 'porcentajeAsistencia', 'datosGrafica'));
    }

    public function destroy(Alumno $alumno)
    {
        $alumno->delete(); 
        return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado exitosamente.');
    }
    public function updatePassword(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'email' => 'required|email',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Verificar si el usuario autenticado coincide con el email proporcionado
        $user = Auth::user();
        if ($user->email !== $request->email) {
            return response()->json(['message' => 'Email no autorizado'], 403);
        }

        // Actualizar la contraseña
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'Contraseña actualizada exitosamente'], 200);
    }

}