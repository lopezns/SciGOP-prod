<?php

namespace App\Http\Controllers;

class AccountsReceivableController extends Controller
{
    public function index()
    {
        return view('placeholder', ['title' => 'Cuentas por Cobrar']);
    }
}