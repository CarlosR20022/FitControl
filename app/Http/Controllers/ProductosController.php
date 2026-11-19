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
            'nombre' => 'required|string|max:255|unique:productos,nombre',
            'categoria' => 'required|in:membresia,visita,suplemento,bebida,snack',
            'precio' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'duracion' => 'nullable|in:dia,semana,mes',
        ]);

        $datos['stock'] = $this->normalizarStock($datos);
        $datos['duracion'] = $this->normalizarDuracion($datos);

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
            'nombre' => 'required|string|max:255|unique:productos,nombre,' . $id,
            'categoria' => 'required|in:membresia,visita,suplemento,bebida,snack',
            'precio' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'duracion' => 'nullable|in:dia,semana,mes',
        ]);

        $datos['stock'] = $this->normalizarStock($datos);
        $datos['duracion'] = $this->normalizarDuracion($datos);

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

    // La duración solo aplica a los planes (membresías y visitas).
    // Si no se eligió, una visita dura un día y una membresía un mes.
    private function normalizarDuracion(array $datos)
    {
        if (!in_array($datos['categoria'], ['membresia', 'visita'])) {
            return null;
        }
        if (in_array($datos['duracion'] ?? null, ['dia', 'semana', 'mes'], true)) {
            return $datos['duracion'];
        }
        return $datos['categoria'] === 'visita' ? 'dia' : 'mes';
    }
}
