<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProgramaController; // Importa tu controlador
use App\Http\Controllers\ModuloController;   // Importa tu controlador
use App\Http\Controllers\RevisionController; // Importa tu controlador
use App\Http\Controllers\HelpController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GlobalSearchController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Rutas protegidas por autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // <-- ¡Cambia esta línea!

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de recursos para tus entidades
    // Esto creará rutas CRUD: index, create, store, show, edit, update, destroy
    Route::resource('programas', ProgramaController::class);
    Route::resource('modulos', ModuloController::class);
    Route::resource('help', HelpController::class); // Si 'help' es una sección administrable

    // Rutas para Docentes (opción A: Resource Controller para docentes)
    Route::resource('docentes', DocenteController::class);
    Route::resource('modulos.revisiones', RevisionController::class)->only(['create', 'store']);
    Route::get('/search', [GlobalSearchController::class, 'index'])->name('global.search');
    Route::post('/revisiones/{revision}/justify', [RevisionController::class, 'justify'])->name('revisiones.justify');
});

require __DIR__.'/auth.php';
