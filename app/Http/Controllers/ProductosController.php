<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Pago;

class ProductosController extends Controller
{
    // Lista de todos los productos
    public function index()
    {
        $productos = Producto::orderBy('categoria')->orderBy('nombre')->get();
        return view('productos.index', compact('productos'));
    }

    // Formulario para agregar un producto nuevo
    public function create()
    {
        return view('productos.form', ['producto' => null]);
    }

    // Guarda el producto nuevo
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|in:membresia,visita,suplemento,bebida,snack',
            'precio' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        $datos['stock'] = $this->normalizarStock($datos);

        Producto::create($datos);

        return redirect()->route('productos.index')->with('success', 'Producto agregado correctamente.');
    }

    // Formulario para editar un producto
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.form', compact('producto'));
    }

    // Guarda los cambios de un producto
    public function update(Request $request, $id)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|in:membresia,visita,suplemento,bebida,snack',
            'precio' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        $datos['stock'] = $this->normalizarStock($datos);

        $producto = Producto::findOrFail($id);
        $producto->update($datos);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    // Elimina un producto (solo si no tiene ventas registradas)
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        if (Pago::where('producto_id', $producto->id)->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar "' . $producto->nombre . '" porque ya tiene ventas registradas.');
        }

        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
    }

    // Las membresías, las visitas y los scoops sueltos no llevan stock
    private function normalizarStock(array $datos)
    {
        if (in_array($datos['categoria'], ['membresia', 'visita'])) {
            return null;
        }
        if (str_contains(strtolower($datos['nombre']), 'scoop')) {
            return null;
        }
        return $datos['stock'] ?? null;
    }
}
