<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AlumnoProfileController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [LoginController::class, 'login']);

Route::get('/password/{email}', [PasswordController::class, 'getPassword']);

Route::post('/password/update', [PasswordController::class, 'updatePassword']);

Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkFromFlutter'])->name('password.email');

Route::middleware(['auth:sanctum', 'role:alumno'])->get('/asistencias', [AsistenciaController::class, 'getAsistenciasGenerales']);

Route::middleware(['auth:sanctum', 'role:alumno'])->get('/grupos-asignados', [AlumnoProfileController::class, 'getGruposAsignados']);

Route::post('/alumnos', [AlumnoController::class, 'store']);

Route::middleware('auth:sanctum')->post('/asistencias', [AsistenciaController::class, 'store']);

Route::middleware('auth:sanctum')->get('/alumno-detalles', [AlumnoController::class, 'getDetalles']);

Route::middleware('auth:sanctum')->get('/asistencia-chart-data', [AlumnoController::class, 'getAsistenciaChartData']);

Route::post('/verificar-asistencia', [AsistenciaController::class, 'verificarAsistencia']);

Route::middleware('auth:sanctum')->post('/alumnos/{alumno}/update-foto-perfil', [AlumnoController::class, 'updateFotoPerfil']);

Route::middleware(['auth:sanctum', 'role:alumno'])->get('/qr-codes-asignados', function (Request $request) {
    $user = $request->user();

    if ($user->role !== 'alumno') {
        return response()->json(['error' => 'No autorizado'], 403);
    }

    $alumno = $user->alumno;

    if (!$alumno) {
        return response()->json(['error' => 'Alumno no encontrado'], 404);
    }

    $qrCodes = \App\Models\QrCode::whereIn('grupo_id', function ($query) use ($alumno) {
        $query->select('grupo_id')
            ->from('grupo_alumno')
            ->where('alumno_id', $alumno->id);
    })->with('grupo', 'materia')->get();

    return response()->json($qrCodes);
});

Route::middleware(['auth:sanctum', 'role:profesor'])->group(function () {
    Route::get('/dashboard-profesor', function () {
        return response()->json(['message' => 'Bienvenido, Profesor']);
    });
});

Route::middleware(['auth:sanctum', 'role:alumno'])->group(function () {
    Route::get('/dashboard-alumno', function () {
        return response()->json(['message' => 'Bienvenido, Alumno']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});