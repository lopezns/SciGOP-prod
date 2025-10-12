<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Display login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Display register form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle a login attempt using database.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:4',
            'remember' => 'nullable',
        ]);

        // Buscar usuario en la base de datos
        $usuario = Usuario::where('email', $validated['email'])
                         ->where('activo', true)
                         ->first();

        if ($usuario && Hash::check($validated['password'], $usuario->password)) {
            Session::put('user', [
                'id' => $usuario->id,
                'name' => $usuario->nombre,
                'email' => $usuario->email,
                'rol_id' => $usuario->rol_id
            ]);

            // Remember cookie (30 days)
            if ($request->boolean('remember')) {
                cookie()->queue('remember_user', $validated['email'], 43200);
            }

            return Redirect::route('cafe.dashboard');
        }

        return Redirect::back()->withErrors(['email' => 'Credenciales inválidas'])->withInput();
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            // Crear nuevo usuario
            $usuario = Usuario::create([
                'rol_id' => 1, // Asignar rol admin por defecto
                'nombre' => $validated['nombre'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'activo' => true
            ]);

            // Auto-login después del registro
            Session::put('user', [
                'id' => $usuario->id,
                'name' => $usuario->nombre,
                'email' => $usuario->email,
                'rol_id' => $usuario->rol_id
            ]);

            return Redirect::route('cafe.dashboard')->with('success', 'Registro exitoso. ¡Bienvenido a SciGOP!');
        } catch (\Exception $e) {
            return Redirect::back()->withErrors(['email' => 'Error al crear la cuenta. Intenta nuevamente.'])->withInput();
        }
    }

    /**
     * Logout and forget session.
     */
    public function logout()
    {
        Session::forget('user');
        cookie()->queue(cookie()->forget('remember_user'));

        return Redirect::route('login');
    }
}
