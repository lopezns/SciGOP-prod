<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','PGC Gestión de Datos')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">PGC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('roles.index') }}">Roles</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Contabilidad</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('transactions.create') }}">Registro de Transacciones</a></li>
                            <li><a class="dropdown-item" href="{{ route('ledger.daily') }}">Libro Diario</a></li>
                            <li><a class="dropdown-item" href="{{ route('ledger.general') }}">Libro Mayor</a></li>
                            <li><a class="dropdown-item" href="{{ route('balances.index') }}">Balances</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Facturación</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('invoices.create') }}">Crear Factura</a></li>
                            <li><a class="dropdown-item" href="{{ route('invoices.index') }}">Lista de Facturas</a></li>
                            <li><a class="dropdown-item" href="{{ route('collections.index') }}">Gestión de Cobros</a></li>
                            <li><a class="dropdown-item" href="{{ route('ar.status') }}">Cuentas por Cobrar</a></li>
                        </ul>
                    </li>
                </ul>
                <form action="{{ route('logout') }}" method="get">
                    <button class="btn btn-light btn-sm" type="submit">Salir</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container mb-5">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>