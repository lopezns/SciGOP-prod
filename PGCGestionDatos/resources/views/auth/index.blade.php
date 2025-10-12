@extends('layouts.app')

@section('title','Autenticación y Roles')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Autenticación y Roles</h1>
    <div class="btn-toolbar mb-3" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group me-2" role="group" aria-label="Usuarios">
            <a href="#" class="btn btn-primary">Crear Usuario</a>
            <a href="#" class="btn btn-outline-primary">Listar Usuarios</a>
        </div>
        <div class="btn-group me-2" role="group" aria-label="Roles">
            <a href="#" class="btn btn-secondary">Crear Rol</a>
            <a href="#" class="btn btn-outline-secondary">Listar Roles</a>
        </div>
        <div class="btn-group" role="group" aria-label="Permisos">
            <a href="#" class="btn btn-info">Asignar Permisos</a>
        </div>
    </div>
    <p class="text-muted">Gestiona usuarios, roles y permisos del sistema. Estos botones serán enlazados a las rutas correspondientes.</p>
</div>
@endsection