@extends('layouts.app')

@section('title', 'Inventario')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Módulo de Inventario</h1>
    <p class="lead">Esta sección se encuentra en construcción. Próximamente podrás gestionar productos, existencias y movimientos de inventario.</p>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
</div>
@endsection