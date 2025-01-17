<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebLoginController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\PasswordResetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/






Route::get('/login', [WebLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [WebLoginController::class, 'login']);
Route::post('/logout', [WebLoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/verify/{token}', [RegisterController::class, 'verify'])->name('verify');

Route::post('/alumnos/csv', [AlumnoController::class, 'subirAlumnosCSV'])->name('alumnos.csv');
Route::get('/alumnos/plantilla', [AlumnoController::class, 'descargarPlantilla'])->name('alumnos.plantilla');


Route::middleware('auth')->group(function () {

    Route::get('/dashboard-profesor', function () {
        return redirect()->route('alumnos.index');
    })->name('dashboard-profesor');
    
    Route::resource('alumnos', AlumnoController::class);
    Route::resource('asistencias', AsistenciaController::class);
    Route::resource('grupos', GrupoController::class);
    
    Route::post('/grupos/{grupo}/assign-alumnos', [GrupoController::class, 'assignAlumnos'])->name('grupos.assign-alumnos');
    Route::resource('qr_codes', QrCodeController::class);
    Route::resource('materias', MateriaController::class);
    Route::get('/asis/{grupo}', [AsistenciaController::class, 'showAsisAlum'])->name('alum.asis');
    Route::get('/listAlumasis/hola/{id}', [AsistenciaController::class, 'showAsisLisAlum'])->name('alumListaasis');
    
});

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/alumnos/search', [AlumnoController::class, 'search'])->name('alumnos.search');
Route::post('/alumnos/add', [AlumnoController::class, 'add'])->name('alumnos.add');

Route::post('/api/materia-by-grupo', [QrCodeController::class, 'getMateriaByGrupo'])->name('materia.by-grupo');


Route::post('/grupos/{grupo}/remove-alumno', [GrupoController::class, 'removeAlumno'])->name('grupos.remove-alumno');
Route::get('/grupos/{grupo}', [GrupoController::class, 'show'])->name('grupos.show');





// Route::get('password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
// Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
// Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

// // Ruta para mostrar el formulario de solicitud de restablecimiento
// Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

// // Ruta para procesar la solicitud de envío del enlace
// Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// // Ruta para mostrar el formulario de restablecimiento con token
// Route::get('password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');

// // Ruta para procesar el restablecimiento de contraseña
// Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.update');


Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request'); // Muestra el formulario para solicitar un enlace de restablecimiento
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email'); // Procesa el envío del enlace de restablecimiento
Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset'); // Muestra el formulario para restablecer la contraseña con el token
Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update'); // Procesa el restablecimiento de contraseña




