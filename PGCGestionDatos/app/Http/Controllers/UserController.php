<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function index()
    {
        return view('placeholder', ['title' => 'Lista de Usuarios']);
    }

    public function create()
    {
        return view('placeholder', ['title' => 'Registro de Usuario']);
    }
}