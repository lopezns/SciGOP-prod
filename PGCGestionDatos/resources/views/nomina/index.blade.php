@extends('layouts.app')

@section('title', 'Nómina')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Módulo de Nómina</h1>
    <p class="lead">Esta sección se encuentra en construcción. Próximamente podrás gestionar cálculos de salarios, deducciones y reportes de nómina.</p>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
</div>
@endsection