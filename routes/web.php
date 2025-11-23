<?php

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\PasswordChangeController;
use Illuminate\Support\Facades\Route;

// Página inicial y post-autenticación: home.blade.php
Route::view('/', 'home')->name('home');

// Consumo de API externa (ejemplo listado productos)
Route::get('external/products', [\App\Http\Controllers\ExternalProductsController::class, 'index'])
    ->middleware('auth')
    ->name('external.products.index');

// Registro vía API (reemplaza registro local de Fortify)
Route::get('register', [AuthApiController::class, 'showRegister'])->name('register');
Route::post('register', [AuthApiController::class, 'register'])->name('register.store');

// Cambio de contraseña vía API
Route::get('password/change', [PasswordChangeController::class, 'show'])->middleware('auth')->name('password.change');
Route::patch('password/change', [PasswordChangeController::class, 'update'])->middleware('auth')->name('password.change.update');
