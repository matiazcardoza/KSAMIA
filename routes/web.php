<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('proyectos', 'proyectos')
    ->middleware(['auth', 'verified'])
    ->name('proyectos');
Route::get('proyectos/{id_proyecto?}/ver-lotes', \App\Livewire\Proyectos\VerLotes\Lotes::class)
    ->middleware(['auth', 'verified'])
    ->name('proyectos.ver-lotes');

Route::get('ventas/{id_tipo_venta?}', \App\Livewire\Ventas\Ventas::class)
    ->middleware(['auth', 'verified'])
    ->name('ventas');

Route::view('usuarios', 'usuarios')
    ->middleware(['auth', 'verified'])
    ->name('usuarios');

Route::view('separados', 'separados')
    ->middleware(['auth', 'verified'])
    ->name('separados');

Route::view('tipo_venta', 'tipo_venta')
    ->middleware(['auth', 'verified'])
    ->name('tipo_venta');

Route::view('tipo_usuario', 'tipo_usuario')
    ->middleware(['auth', 'verified'])
    ->name('tipo_usuario');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
