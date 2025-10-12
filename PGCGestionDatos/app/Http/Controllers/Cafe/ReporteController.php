<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\FacturaVenta;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index()
    {
        return view('cafe.reportes.index');
    }
}