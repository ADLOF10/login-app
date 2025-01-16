<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebLoginController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\RegisterController;

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
    Route::get('/grupos/{grupo}', [GrupoController::class, 'show'])->name('grupos.show');
    Route::post('/grupos/{grupo}/assign-alumnos', [GrupoController::class, 'assignAlumnos'])->name('grupos.assign-alumnos');
    Route::resource('qr_codes', QrCodeController::class);
    Route::resource('materias', MateriaController::class);
    Route::get('/asis/{grupo}', [GrupoController::class, 'showAsisAlum'])->name('alum.asis');
    Route::get('/listAlumasis/hola/{id}', [GrupoController::class, 'showAsisLisAlum'])->name('alumListaasis');
    
});

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/alumnos/search', [AlumnoController::class, 'search'])->name('alumnos.search');
Route::post('/alumnos/add', [AlumnoController::class, 'add'])->name('alumnos.add');

Route::post('/api/materia-by-grupo', [QrCodeController::class, 'getMateriaByGrupo'])->name('materia.by-grupo');


Route::post('/grupos/{grupo}/remove-alumno', [GrupoController::class, 'removeAlumno'])->name('grupos.remove-alumno');

