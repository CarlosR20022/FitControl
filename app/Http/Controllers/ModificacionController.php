<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ModificacionController extends Controller
{
    // 1. Pantalla principal del menú de Modificaciones
    public function index()
    {
        return view('modificaciones.index');
    }

    // 2. Pantalla con la lista de productos para cambiar precios
    public function precios()
    {
        // Traemos todos los productos (Suplementos, Bebidas, Membresías)
        $productos = Producto::orderBy('categoria')->orderBy('nombre')->get();
        return view('modificaciones.precios', compact('productos'));
    }

    // 3. Guarda el nuevo precio en la base de datos
    public function actualizarPrecio(Request $request, $id)
    {
        $request->validate([
            'precio' => 'required|numeric|min:0'
        ]);

        $producto = Producto::findOrFail($id);
        $producto->precio = $request->precio;
        $producto->save();

        // Regresa a la misma pantalla para seguir editando si es necesario
        return redirect()->back(); 
    }
}