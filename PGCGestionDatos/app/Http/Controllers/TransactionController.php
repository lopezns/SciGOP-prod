<?php

namespace App\Http\Controllers;

class TransactionController extends Controller
{
    public function create()
    {
        return view('placeholder', ['title' => 'Registro de Transacciones']);
    }
}