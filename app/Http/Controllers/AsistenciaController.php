<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Alumno;
use App\Models\Grupo;
use App\Models\QrCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AsistenciaController extends Controller
{
     
     public function index()
     {
         //$asistencias = Asistencia::with('alumno', 'grupo')->get();
        // $user_prof=Auth::user()->name;
         $asistencias = DB::table('asistencias')
         ->join('alumnos', 'asistencias.alumno_id', '=', 'alumnos.id')
         ->join('grupos', 'asistencias.grupo_id', '=', 'grupos.id')
         ->join('materias', 'asistencias.materia_id', '=', 'materias.id')
         ->select('asistencias.*', 'alumnos.nombre','alumnos.apellidos','grupos.nombre_grupo', 'materias.nombre as nombre_materia')
         //->where('users.name',$user_prof)
         ->get(); 

         return view('asistencias.index', compact('asistencias'));
     }

     public function verificarAsistencia(Request $request)
     {
         $request->validate([
             'qr_code_id' => 'required|integer',
         ]);
     
         try {
             $user = $request->user();
             $alumno = $user->alumno;
     
             if (!$alumno) {
                 return response()->json(['error' => 'Alumno no encontrado'], 404);
             }
     
             $asistenciaExistente = Asistencia::where('qr_code_id', $request->qr_code_id)
                 ->where('alumno_id', $alumno->id)
                 ->exists();
     
             return response()->json(['asistenciaRegistrada' => $asistenciaExistente], 200);
         } catch (\Exception $e) {
             \Log::error('Error al verificar asistencia: ' . $e->getMessage());
             return response()->json(['error' => 'Error interno al verificar asistencia'], 500);
         }
     }

     public function create()
     {
         $alumnos = Alumno::all();
         $grupos = Grupo::all();
         return view('asistencias.create', compact('alumnos', 'grupos'));
     }
 
     public function store(Request $request)
    {
        try {

            $horaClase = $request->input('hora_clase');
            if (strlen($horaClase) === 5) {
                $horaClase .= ':00';
            }
            $request->merge(['hora_clase' => $horaClase]);


            $validated = $request->validate([
                'grupo_id' => 'required|exists:grupos,id',
                'materia_id' => 'required|exists:materias,id',
                'hora_clase' => 'required|date_format:H:i:s',
            ]);

            $alumnoId = $request->user()->alumno->id;
            $fechaActual = now()->toDateString();
            $horaActual = now();


            $asistenciaExistente = Asistencia::where('alumno_id', $alumnoId)
                ->where('grupo_id', $request->input('grupo_id'))
                ->where('materia_id', $request->input('materia_id'))
                ->where('fecha', $fechaActual)
                ->first();

            if ($asistenciaExistente) {
                return response()->json([
                    'error' => 'Ya existe una asistencia registrada para este alumno hoy.'
                ], 409);
            }


            $qrCode = QrCode::where('grupo_id', $request->input('grupo_id'))
                ->where('materia_id', $request->input('materia_id'))
                ->where('fecha_clase', $fechaActual)
                ->first();

            if (!$qrCode) {
                return response()->json(['error' => 'Código QR no encontrado.'], 404);
            }


            $horaClaseCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $qrCode->hora_clase);
            $diferenciaMinutos = $horaClaseCarbon->diffInMinutes($horaActual, false); 

            $tipo = 'inasistencia'; 
            if ($diferenciaMinutos >= 0 && $diferenciaMinutos <= $qrCode->asistencia) {
                $tipo = 'asistencia';
            } elseif ($diferenciaMinutos > $qrCode->asistencia && $diferenciaMinutos <= $qrCode->retardo) {
                $tipo = 'retardo';
            }


            $asistencia = Asistencia::create([
                'alumno_id' => $alumnoId,
                'grupo_id' => $request->input('grupo_id'),
                'materia_id' => $request->input('materia_id'),
                'fecha' => $fechaActual,
                'hora_registro' => $horaActual->toTimeString(),
                'hora_clase' => $qrCode->hora_clase,
                'fecha_clase' => $qrCode->fecha_clase,
                'tipo' => $tipo,
                'estado' => 'registrada',
                'qr_code_id' => $qrCode->id, 
            ]);

            return response()->json(['success' => 'Asistencia registrada correctamente.', 'data' => $asistencia], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validación fallida', 'detalles' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al registrar asistencia.', 'exception' => $e->getMessage()], 500);
        }
    }

     public function show(Asistencia $asistencia)
     {
         $asistencia->load('alumno', 'grupo');
         return view('asistencias.show', compact('asistencia'));
     }
 
     public function edit(Asistencia $asistencia)
     {
         $alumnos = Alumno::all();
         $grupos = Grupo::all();
         return view('asistencias.edit', compact('asistencia', 'alumnos', 'grupos'));
     }
     
     public function update(Request $request, Asistencia $asistencia)
     {
         $request->validate([
             'alumno_id' => 'required|exists:alumnos,id',
             'grupo_id' => 'required|exists:grupos,id',
             'tipo' => 'required|string',
             'estado' => 'required|string',
         ]);
 
         $asistencia->update($request->all());
         return redirect()->route('asistencias.index')->with('success', 'Asistencia actualizada correctamente.');
     }
 
     public function registrarAsistencia(Request $request)
     {
         $request->validate([
             'codigo' => 'required|string',
             'alumno_id' => 'required|exists:alumnos,id',
         ]);
 
         $codigoDecodificado = json_decode(base64_decode($request->codigo), true);
         $qrCode = QrCode::find($codigoDecodificado['id']);
 
         if (!$qrCode) {
             return response()->json(['error' => 'Código QR inválido.'], 404);
         }
 
         $horaActual = now();
         $horaClase = $qrCode->hora_clase;
         $expira = $qrCode->expira_at;
 
         if ($horaActual->greaterThan($expira)) {
             return response()->json(['error' => 'El código QR ha expirado.'], 400);
         }
 
         $tipo = 'asistencia';
         if ($horaActual->diffInMinutes($horaClase) > $qrCode->retardo) {
             $tipo = 'retardo';
         }
 
         Asistencia::create([
             'alumno_id' => $request->alumno_id,
             'grupo_id' => $qrCode->grupo_id,
             'fecha' => $horaActual->toDateString(),
             'hora_registro' => $horaActual->toTimeString(),
             'tipo' => $tipo,
             'estado' => 'registrada',
         ]);
 
         return response()->json(['success' => 'Asistencia registrada exitosamente.'], 200);
     }
 
     public function destroy(Asistencia $asistencia)
     {
         $asistencia->delete();
         return redirect()->route('asistencias.index')->with('success', 'Asistencia eliminada correctamente.');
     }
}
