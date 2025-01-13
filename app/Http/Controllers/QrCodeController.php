<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Http\Request;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeController extends Controller
{
    public function index()
    {
        $qrCodes = QrCode::with('grupo', 'materia')->get(); 
        return view('qr_codes.index', compact('qrCodes'));
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
            'hora_clase' => 'required|date_format:H:i',
            'fecha_clase' => 'required|date', 
            'asistencia' => 'required|integer|min:1',
            'retardo' => 'required|integer|min:1',
            'inasistencia' => 'required|integer|min:1',
        ]);
        
        $qrCodeData = [
            'grupo_id' => $request->grupo_id,
            'materia_id' => $request->materia_id,
            'tipo' => $request->tipo,
            'hora_clase' => $request->hora_clase,
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
            'fecha_clase' => $request->fecha_clase, 
            'asistencia' => $request->asistencia,
            'retardo' => $request->retardo,
            'inasistencia' => $request->inasistencia,
            'expira_at' => now()->addMinutes($request->inasistencia + 2),
        ]);
        

        return redirect()->route('qr_codes.index')->with('success', 'Código QR creado exitosamente.');
    }

    public function destroy(QrCode $qrCode)
    {
        $qrCode->delete(); 
        return redirect()->route('qr_codes.index')->with('success', 'Código QR eliminado exitosamente.');
    }
}