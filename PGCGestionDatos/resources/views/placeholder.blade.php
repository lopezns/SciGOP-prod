@extends('layouts.app')

@section('title', $title ?? 'Vista')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="card shadow-sm w-100" style="max-width: 540px;">
            <div class="card-body text-center">
                <h1 class="card-title mb-3">{{ $title ?? 'Vista' }}</h1>
                <p class="card-text text-muted">Contenido en construcción. Próximamente encontrarás aquí la funcionalidad.</p>
                <a href="{{ url()->previous() }}" class="btn btn-outline-primary mt-3">Volver</a>
            </div>
        </div>
    </div>
@endsection