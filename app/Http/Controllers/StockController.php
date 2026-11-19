<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class StockController extends Controller
{
    // Consulta de stock: bebidas, snacks y suplementos en bote.
    // No incluye membresías, visitas ni scoops sueltos.
    // Con ?bajo=1 solo muestra los productos con stock menor a 3.
    public function index(Request $request)
    {
        $soloBajo = $request->boolean('bajo');

        $query = Producto::conStock()->orderByRaw('stock IS NULL, stock ASC');

        if ($soloBajo) {
            $query->whereNotNull('stock')->where('stock', '<', 3);
        }

        $productos = $query->get();

        $totalBajo = Producto::conStock()
            ->whereNotNull('stock')
            ->where('stock', '<', 3)
            ->count();

        return view('stock.index', compact('productos', 'soloBajo', 'totalBajo'));
    }
}
