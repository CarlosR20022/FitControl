<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

// Módulo Usuarios (Modificaciones): cuentas de administrador del sistema.
// Todos los usuarios son administradores e inician sesión con su ID.
class UsuariosController extends Controller
{
    // Lista de usuarios
    public function index()
    {
        $usuarios = User::orderBy('codigo')->get();
        return view('modificaciones.usuarios.index', compact('usuarios'));
    }

    // Formulario para agregar un usuario (el ID se asigna automático)
    public function create()
    {
        $siguienteId = $this->siguienteCodigo();
        return view('modificaciones.usuarios.form', compact('siguienteId'));
    }

    // Guarda el usuario nuevo con el siguiente ID disponible
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:3',
        ]);

        $codigo = $this->siguienteCodigo();

        User::create([
            'codigo' => $codigo,
            'name' => $request->name,
            // El correo no se usa para iniciar sesión; es solo un dato
            // obligatorio de la tabla. Cada usuario tiene uno distinto.
            'email' => 'usuario' . $codigo . '@fitcontrol.local',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('modificaciones.usuarios')
            ->with('exito', 'Usuario ' . $request->name . ' creado con el ID ' . $codigo . '.');
    }

    // Formulario para editar un usuario (solo nombre y fecha de creación)
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('modificaciones.usuarios.form', compact('usuario'));
    }

    // Guarda el nombre y la fecha de creación corregidos
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'fecha_creacion' => 'required|date',
        ]);

        $usuario->name = $request->name;
        $usuario->created_at = Carbon::parse($request->fecha_creacion);
        $usuario->save();

        return redirect()->route('modificaciones.usuarios')
            ->with('exito', 'Usuario actualizado.');
    }

    // Siguiente ID disponible: el mayor ID numérico + 1, con 3 dígitos (002, 003...)
    private function siguienteCodigo(): string
    {
        $max = User::all()->map(fn ($u) => (int) $u->codigo)->max();
        return str_pad(($max ?: 0) + 1, 3, '0', STR_PAD_LEFT);
    }
}
