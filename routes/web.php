<?php

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\ProductProxyController;
use App\Http\Controllers\ServiceProxyController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PurchaseAdminController;
use Illuminate\Support\Facades\Route;

// Página inicial y post-autenticación: home.blade.php
Route::view('/', 'home')->name('home');

// Consumo de API externa (ejemplo listado productos)
Route::get('external/products', [\App\Http\Controllers\ExternalProductsController::class, 'index'])
    ->middleware('auth')
    ->name('external.products.index');

// Productos proxy API
Route::get('productos', [ProductProxyController::class, 'catalogo'])->name('productos.index');Route::get('productos/{id}', [ProductProxyController::class, 'show'])->whereNumber('id')->name('productos.show');
Route::post('productos', [ProductProxyController::class, 'store'])->middleware(['auth','admin'])->name('productos.store');
Route::put('productos/{id}', [ProductProxyController::class, 'update'])->middleware(['auth','admin'])->whereNumber('id')->name('productos.update');
Route::delete('productos/{id}', [ProductProxyController::class, 'destroy'])->middleware(['auth','admin'])->whereNumber('id')->name('productos.destroy');
// Panel HTML admin productos
Route::get('productos/admin', [ProductProxyController::class, 'admin'])->middleware(['auth','admin'])->name('productos.admin');
Route::post('productos/admin/create', [ProductProxyController::class, 'adminStore'])->middleware(['auth','admin'])->name('productos.admin.create');
Route::post('productos/admin/{id}/update', [ProductProxyController::class, 'adminUpdate'])->middleware(['auth','admin'])->whereNumber('id')->name('productos.admin.update');
Route::post('productos/admin/{id}/delete', [ProductProxyController::class, 'adminDestroy'])->middleware(['auth','admin'])->whereNumber('id')->name('productos.admin.delete');

// Servicios proxy API
Route::get('servicios', [ServiceProxyController::class, 'index'])->name('servicios.index');
Route::get('servicios/{id}', [ServiceProxyController::class, 'show'])->whereNumber('id')->name('servicios.show');
Route::post('servicios', [ServiceProxyController::class, 'store'])->middleware(['auth','admin'])->name('servicios.store');
Route::put('servicios/{id}', [ServiceProxyController::class, 'update'])->middleware(['auth','admin'])->whereNumber('id')->name('servicios.update');
Route::delete('servicios/{id}', [ServiceProxyController::class, 'destroy'])->middleware(['auth','admin'])->whereNumber('id')->name('servicios.destroy');
// Panel HTML admin servicios
Route::get('servicios/admin', [ServiceProxyController::class, 'admin'])->middleware(['auth','admin'])->name('servicios.admin');
Route::post('servicios/admin/create', [ServiceProxyController::class, 'adminStore'])->middleware(['auth','admin'])->name('servicios.admin.create');
Route::post('servicios/admin/{id}/update', [ServiceProxyController::class, 'adminUpdate'])->middleware(['auth','admin'])->whereNumber('id')->name('servicios.admin.update');
Route::post('servicios/admin/{id}/delete', [ServiceProxyController::class, 'adminDestroy'])->middleware(['auth','admin'])->whereNumber('id')->name('servicios.admin.delete');

// Registro vía API (reemplaza registro local de Fortify)
Route::get('register', [AuthApiController::class, 'showRegister'])->name('register');
Route::post('register', [AuthApiController::class, 'register'])->name('register.store');

// Cambio de contraseña vía API
Route::get('password/change', [PasswordChangeController::class, 'show'])->middleware('auth')->name('password.change');
Route::patch('password/change', [PasswordChangeController::class, 'update'])->middleware('auth')->name('password.change.update');

// Gestión usuarios admin
Route::get('admin/users', [UserManagementController::class, 'index'])->middleware(['auth','admin'])->name('admin.users');
Route::post('admin/users/create', [UserManagementController::class, 'store'])->middleware(['auth','admin'])->name('admin.users.create');
Route::post('admin/users/{id}/role', [UserManagementController::class, 'updateRole'])->middleware(['auth','admin'])->whereNumber('id')->name('admin.users.role');

// Finanzas admin
Route::get('admin/finanzas', [FinanceController::class, 'index'])->middleware(['auth','admin'])->name('admin.finanzas');

// Carrito
Route::get('carrito', [CartController::class, 'index'])->name('carrito.index');
Route::post('carrito/agregar/producto/{id}', [CartController::class, 'addProduct'])->whereNumber('id')->name('carrito.add.product');
Route::post('carrito/agregar/servicio/{id}', [CartController::class, 'addService'])->whereNumber('id')->name('carrito.add.service');
Route::post('carrito/producto/{id}/incrementar', [CartController::class, 'incrementProduct'])->whereNumber('id')->name('carrito.product.increment');
Route::post('carrito/producto/{id}/disminuir', [CartController::class, 'decrementProduct'])->whereNumber('id')->name('carrito.product.decrement');
Route::post('carrito/producto/{id}/eliminar', [CartController::class, 'removeProduct'])->whereNumber('id')->name('carrito.product.remove');
Route::post('carrito/vaciar', [CartController::class, 'clear'])->name('carrito.clear');
Route::post('carrito/servicio/{id}/eliminar', [CartController::class, 'removeService'])->whereNumber('id')->name('carrito.service.remove');
Route::post('carrito/comprar', [CartController::class, 'checkout'])->name('carrito.checkout');
Route::post('carrito/generar-ticket', [CartController::class, 'generateTicket'])->name('carrito.ticket');

// Agendar servicio (formulario + guardar)
Route::get('servicios/{id}/agendar', [CartController::class, 'scheduleForm'])->whereNumber('id')->name('servicios.schedule.form');
Route::post('servicios/{id}/agendar', [CartController::class, 'scheduleStore'])->whereNumber('id')->name('servicios.schedule.store');

// Compras admin
Route::get('admin/compras', [PurchaseAdminController::class, 'index'])->middleware(['auth','admin'])->name('admin.compras');
Route::get('admin/compras/{id}', [PurchaseAdminController::class, 'show'])->middleware(['auth','admin'])->whereNumber('id')->name('admin.compras.show');
Route::get('admin/compras/{id}/ticket', [PurchaseAdminController::class, 'ticket'])->middleware(['auth','admin'])->whereNumber('id')->name('admin.compras.ticket');
