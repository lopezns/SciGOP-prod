<?php

namespace App\Http\Controllers;

class LedgerController extends Controller
{
    public function daily()
    {
        return view('placeholder', ['title' => 'Libro Diario']);
    }

    public function general()
    {
        return view('placeholder', ['title' => 'Libro Mayor']);
    }

    public function balances()
    {
        return view('placeholder', ['title' => 'Balances']);
    }
}