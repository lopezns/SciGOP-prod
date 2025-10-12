<?php

namespace App\Http\Controllers;

class RoleController extends Controller
{
    public function index()
    {
        return view('placeholder', ['title' => 'Gestión de Roles']);
    }
}