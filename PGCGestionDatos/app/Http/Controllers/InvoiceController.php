<?php

namespace App\Http\Controllers;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('placeholder', ['title' => 'Lista de Facturas']);
    }

    public function create()
    {
        return view('placeholder', ['title' => 'Crear Factura Electrónica']);
    }
}