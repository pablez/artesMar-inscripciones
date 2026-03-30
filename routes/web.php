<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;

Route::view('/',                'home')->name('home');
use App\Models\Horario;

Route::get('/horarios', function () {
    $horarios = Horario::with('disciplina')->orderBy('dias')->orderBy('hora')->get();
    return view('horarios', compact('horarios'));
})->name('horarios');
Route::view('/artes-marciales', 'artes-marciales')->name('artes');
use App\Models\Afiliacion;

Route::get('/afiliaciones', function () {
    $afiliaciones = Afiliacion::orderBy('created_at', 'desc')->get();
    return view('afiliaciones', compact('afiliaciones'));
})->name('afiliaciones');
Route::view('/ubicacion',       'ubicacion')->name('ubicacion');

// Autenticación (solo para usuarios no autenticados)
Route::middleware('guest')->group(function () {
    Route::view('/login',           'login')->name('login');
    Route::view('/registro',        'register')->name('registro');
    Route::post('/validar-registro', [LoginController::class, 'register'])->name('validar-registro');
    Route::post('/inicia-sesion',    [LoginController::class, 'login'])->name('inicia-sesion');
});

// Logout (para usuarios autenticados)
Route::post('/logout',           [LoginController::class, 'logout'])->name('logout');
Route::get('/logout',            [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Inscripciones (usuarios autenticados)
    Route::get('/inscripciones',               [InscripcionController::class, 'create'])->name('inscripciones');
    Route::post('/inscripciones',              [InscripcionController::class, 'store'])->name('inscripciones.store');
    Route::get('/inscripcion-exitosa/{id}',    [InscripcionController::class, 'success'])->name('inscripcion.success');
    Route::post('/inscripciones/{id}/comprobante', [InscripcionController::class, 'comprobante'])->name('inscripciones.comprobante');
    
    // Panel administrativo
    Route::get('/admin/inscripciones',         [AdminController::class, 'inscripciones'])->name('admin.inscripciones');
    Route::delete('/admin/inscripciones/{id}', [AdminController::class, 'destroyInscripcion'])->name('admin.inscripciones.destroy');
    Route::put('/admin/inscripciones/{id}/estado', [AdminController::class, 'updateEstado'])->name('admin.inscripciones.estado');
    Route::get('/admin/disciplinas',           [AdminController::class, 'disciplinas'])->name('admin.disciplinas');
    Route::post('/admin/disciplinas',           [AdminController::class, 'storeDisciplina'])->name('admin.disciplinas.store');
    Route::put('/admin/disciplinas/{id}',      [AdminController::class, 'updateDisciplina'])->name('admin.disciplinas.update');
    Route::delete('/admin/disciplinas/{id}',   [AdminController::class, 'destroyDisciplina'])->name('admin.disciplinas.destroy');
    
    // Gestión de afiliaciones
    Route::get('/admin/afiliaciones',          [AdminController::class, 'afiliaciones'])->name('admin.afiliaciones');
    Route::post('/admin/afiliaciones',         [AdminController::class, 'storeAfiliacion'])->name('admin.afiliaciones.store');
    Route::put('/admin/afiliaciones/{id}',     [AdminController::class, 'updateAfiliacion'])->name('admin.afiliaciones.update');
    Route::delete('/admin/afiliaciones/{id}',  [AdminController::class, 'destroyAfiliacion'])->name('admin.afiliaciones.destroy');
    
    // Gestión de horarios
    Route::get('/admin/horarios',              [AdminController::class, 'horarios'])->name('admin.horarios');
    Route::post('/admin/horarios',             [AdminController::class, 'storeHorario'])->name('admin.horarios.store');
    Route::put('/admin/horarios/{id}',         [AdminController::class, 'updateHorario'])->name('admin.horarios.update');
    Route::delete('/admin/horarios/{id}',      [AdminController::class, 'destroyHorario'])->name('admin.horarios.destroy');
    
    Route::get('/admin/deportes',              [AdminController::class, 'deportes'])->name('admin.deportes');
    Route::post('/admin/deportes',             [AdminController::class, 'storeDeporte'])->name('admin.deportes.store');
    Route::put('/admin/deportes/{id}',         [AdminController::class, 'updateDeporte'])->name('admin.deportes.update');
    Route::delete('/admin/deportes/{id}',      [AdminController::class, 'destroyDeporte'])->name('admin.deportes.destroy');
});
