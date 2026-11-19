<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ModificacionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\RenovacionController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\LoginController;

// Inicio de sesión (sin autenticación)
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Todo lo demás requiere haber iniciado sesión
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index']);

    // Rutas para agregar clientes
    Route::get('/clientes/nuevo', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/vigentes', [ClienteController::class, 'vigentes'])->name('clientes.vigentes');
    Route::get('/clientes/vencidos', [ClienteController::class, 'vencidos'])->name('clientes.vencidos');
    Route::get('/clientes/por-vencer', [ClienteController::class, 'porVencer'])->name('clientes.porVencer');
    Route::get('/clientes/historico', [ClienteController::class, 'historico'])->name('clientes.historico');
    Route::get('/clientes/nuevos', [ClienteController::class, 'nuevos'])->name('clientes.nuevos');
    Route::get('/clientes/bajas', [ClienteController::class, 'bajas'])->name('clientes.bajas');
    Route::get('/clientes/{id}/renovar', [RenovacionController::class, 'create'])->name('renovaciones.create');
    Route::post('/clientes/{id}/renovar', [RenovacionController::class, 'store'])->name('renovaciones.store');
    Route::get('/ingresos/dia', [DashboardController::class, 'ingresosDia'])->name('ingresos.dia');
    Route::get('/consulta', [ClienteController::class, 'consulta'])->name('clientes.consulta');
    Route::get('/consulta/stock', [StockController::class, 'index'])->name('consulta.stock');
    Route::get('/productos', [ProductosController::class, 'index'])->name('productos.index');
    Route::get('/productos/nuevo', [ProductosController::class, 'create'])->name('productos.create');
    Route::post('/productos', [ProductosController::class, 'store'])->name('productos.store');
    Route::get('/productos/{id}/editar', [ProductosController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{id}', [ProductosController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}', [ProductosController::class, 'destroy'])->name('productos.destroy');
    Route::get('/reporte/pdf', [ReporteController::class, 'generar'])->name('reporte.pdf');
    Route::post('/reporte/enviar', [ReporteController::class, 'enviar'])->name('reporte.enviar');
    Route::post('/ventas/rapida', [VentaController::class, 'storeRapida'])->name('ventas.rapida');
    Route::get('/modificaciones', [ModificacionController::class, 'index'])->name('modificaciones.index');
    Route::get('/modificaciones/precios', [ModificacionController::class, 'precios'])->name('modificaciones.precios');
    Route::post('/modificaciones/precios/{id}', [ModificacionController::class, 'actualizarPrecio'])->name('modificaciones.actualizarPrecio');
    Route::get('/modificaciones/clientes', [ClienteController::class, 'adminIndex'])->name('modificaciones.clientes');
    Route::get('/modificaciones/clientes/{id}/editar', [ClienteController::class, 'adminEdit'])->name('modificaciones.clientes.editar');
    Route::put('/modificaciones/clientes/{id}', [ClienteController::class, 'adminUpdate'])->name('modificaciones.clientes.actualizar');
    Route::get('/modificaciones/usuarios', [UsuariosController::class, 'index'])->name('modificaciones.usuarios');
    Route::get('/modificaciones/usuarios/nuevo', [UsuariosController::class, 'create'])->name('modificaciones.usuarios.create');
    Route::post('/modificaciones/usuarios', [UsuariosController::class, 'store'])->name('modificaciones.usuarios.store');
    Route::get('/modificaciones/usuarios/{id}/editar', [UsuariosController::class, 'edit'])->name('modificaciones.usuarios.edit');
    Route::put('/modificaciones/usuarios/{id}', [UsuariosController::class, 'update'])->name('modificaciones.usuarios.update');
});
