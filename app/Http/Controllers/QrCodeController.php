<?php 

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Http\Request;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QrCodeController extends Controller
{
    public function index()
    {
        $user_prof = Auth::user()->name;
        $qrCodes = DB::table('qr_codes')
            ->join('grupos', 'qr_codes.grupo_id', '=', 'grupos.id')
            ->join('materias', 'qr_codes.materia_id', '=', 'materias.id')
            ->join('users', 'materias.user_id', '=', 'users.id')
            ->select('qr_codes.*', 'grupos.nombre_grupo as grupo_nombre', 'materias.nombre as materia_nombre')
            ->where('users.name', $user_prof)
            ->get()
            ->map(function ($qrCode) {
                // Convertir created_at y cualquier otro campo necesario en instancias de Carbon
                $qrCode->created_at = Carbon::parse($qrCode->created_at);
                return $qrCode;
            });

        return view('qr_codes.index', compact('qrCodes', 'user_prof'));
    }

    public function create()
    {
        $grupos = Grupo::all(); 
        $materias = Materia::all(); 
        return view('qr_codes.create', compact('grupos', 'materias')); 
    }

    public function store(Request $request)
{
    $request->validate([
        'grupo_id' => 'required|exists:grupos,id',
        'materia_id' => 'required|exists:materias,id',
        'tipo' => 'required|in:asistencia,retardo',
        'hora_clase' => [
            'required',
            'date_format:H:i',
            function ($attribute, $value, $fail) {
                $horaInicio = strtotime($value);
                $minHora = strtotime('07:00');
                $maxHora = strtotime('18:00');

                if ($horaInicio < $minHora || $horaInicio > $maxHora) {
                    $fail('La hora de clase debe estar entre las 07:00 y las 18:00.');
                }
            },
        ],
        'fin_clase' => [
            'required',
            'date_format:H:i',
            function ($attribute, $value, $fail) use ($request) {
                if (!$request->hora_clase) {
                    $fail('Primero debes ingresar la hora de inicio.');
                    return;
                }

                $horaInicio = strtotime($request->hora_clase);
                $horaFin = strtotime($value);
                $limiteMaximo = strtotime('19:00');

                if ($horaInicio >= strtotime('15:01') && $horaInicio <= strtotime('18:00') && $horaFin > $limiteMaximo) {
                    $fail('Si la hora de inicio está entre las 15:01 y las 18:00, la hora de fin no puede exceder las 19:00.');
                }

                if ($horaFin > $limiteMaximo) {
                    $fail('La hora de fin no puede ser mayor a las 19:00.');
                }

                if ($horaFin < $horaInicio + 3600) {
                    $fail('La hora de fin debe ser al menos 1 hora después de la hora de inicio.');
                }

                if ($horaFin > $horaInicio + 14400) {
                    $fail('La hora de fin no puede ser más de 4 horas después de la hora de inicio.');
                }
            },
        ],
        'fecha_clase' => [
            'required',
            'date',
            function ($attribute, $value, $fail) {
                $fechaActual = now();
                $fechaMaxima = now()->addMonths(6);

                if (strtotime($value) > strtotime($fechaMaxima)) {
                    $fail('La fecha de clase no puede ser mayor a 6 meses desde hoy.');
                }
            },
        ],
        'asistencia' => 'required|integer|min:5',
        'retardo' => 'required|integer|min:1',
        'inasistencia' => 'required|integer|min:1',
    ], [
        'hora_clase.date_format' => 'El formato de la hora de clase debe ser válido (HH:mm).',
        'fin_clase.date_format' => 'El formato de la hora de fin debe ser válido (HH:mm).',
        'asistencia.min' => 'El tiempo para asistencia debe ser al menos de 5 minutos.',
    ]);

    $qrCodeData = [
        'grupo_id' => $request->grupo_id,
        'materia_id' => $request->materia_id,
        'tipo' => $request->tipo,
        'hora_clase' => $request->hora_clase,
        'fin_clase' => $request->fin_clase,
        'fecha_clase' => $request->fecha_clase,
    ];

    \Log::info('Datos del QR antes de codificar:', $qrCodeData);

    $qrCode = Builder::create()
        ->writer(new PngWriter())
        ->data(json_encode($qrCodeData))
        ->size(200)
        ->margin(10)
        ->build();

    $qrCodeBase64 = base64_encode($qrCode->getString());

    QrCode::create([
        'grupo_id' => $request->grupo_id,
        'materia_id' => $request->materia_id,
        'tipo' => $request->tipo,
        'codigo' => $qrCodeBase64,
        'hora_clase' => $request->hora_clase,
        'fin_clase' => $request->fin_clase,
        'fecha_clase' => $request->fecha_clase,
        'asistencia' => $request->asistencia,
        'retardo' => $request->retardo,
        'inasistencia' => $request->inasistencia,
        'expira_at' => now()->addMinutes($request->asistencia + $request->retardo + $request->inasistencia), // Corrección aplicada aquí
    ]);

    return redirect()->route('qr_codes.index')->with('success', 'Código QR creado exitosamente.');
}


    public function destroy(QrCode $qrCode)
    {
        $qrCode->delete(); 
        return redirect()->route('qr_codes.index')->with('success', 'Código QR eliminado exitosamente.');
    }

    public function getMateriaByGrupo(Request $request)
    {
        $grupoId = $request->input('grupo_id');
    
        $grupo = \App\Models\Grupo::find($grupoId);
    
        if (!$grupo) {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }
    
        $materia = $grupo->materia;
    
        return response()->json([
            'materia_id' => $materia->id,
            'materia_nombre' => $materia->nombre,
        ]);
    }
}
