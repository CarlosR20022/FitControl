<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    // Muestra el formulario de inicio de sesión
    public function show()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    // Valida el ID y la contraseña contra la tabla de usuarios
    public function login(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:50',
            'password' => 'required|string',
        ]);

        $usuario = User::where('codigo', $request->codigo)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->withErrors(['codigo' => 'ID o contraseña incorrectos.'])->withInput();
        }

        Auth::login($usuario, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    // Cierra la sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
