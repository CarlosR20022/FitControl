<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ModificacionController;
use App\Http\Controllers\ReporteController;

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
Route::get('/ingresos/dia', [DashboardController::class, 'ingresosDia'])->name('ingresos.dia');
Route::get('/consulta', [ClienteController::class, 'consulta'])->name('clientes.consulta');
Route::get('/reporte/pdf', [ReporteController::class, 'generar'])->name('reporte.pdf');
Route::post('/ventas/rapida', [VentaController::class, 'storeRapida'])->name('ventas.rapida');
Route::get('/modificaciones', [ModificacionController::class, 'index'])->name('modificaciones.index');
Route::get('/modificaciones/precios', [ModificacionController::class, 'precios'])->name('modificaciones.precios');
Route::post('/modificaciones/precios/{id}', [ModificacionController::class, 'actualizarPrecio'])->name('modificaciones.actualizarPrecio');