<?php

namespace App\Http\Controllers;

class CollectionController extends Controller
{
    public function index()
    {
        return view('placeholder', ['title' => 'Gestión de Cobros']);
    }
}