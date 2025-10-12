<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SciGOP - @yield('title', 'Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-orange-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-72 bg-gradient-to-b from-amber-800 to-amber-900 text-white relative min-h-screen flex flex-col">
            <div class="p-6 flex-1">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-8 h-8 bg-orange-200 rounded-lg flex items-center justify-center">
                        <span class="text-amber-800 font-bold text-lg">☕</span>
                    </div>
                    <h1 class="font-display text-xl font-bold text-amber-950" style="color: #451a03 !important;">SciGOP</h1>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('cafe.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.dashboard') ? 'bg-amber-700' : 'hover:bg-amber-700' }} transition-colors duration-200" style="color: #451a03 !important;">
                        <span>📊</span>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    
                    <!-- Punto de Venta -->
                    <a href="{{ route('cafe.pos.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.pos.*') ? 'bg-amber-700' : 'hover:bg-amber-700' }} transition-colors duration-200" style="color: #451a03 !important;">
                        <span>💳</span>
                        <span class="font-medium">Punto de Venta</span>
                        <span class="ml-auto bg-green-500 text-white text-xs px-2 py-1 rounded-full">POS</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <h3 class="text-xs uppercase tracking-wider font-semibold" style="color: #451a03 !important;">Ventas</h3>
                    </div>
                    
                    <a href="{{ route('cafe.ventas.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.ventas.index') ? 'bg-amber-700' : 'hover:bg-amber-700' }} transition-colors duration-200" style="color: #451a03 !important;">
                        <span>📋</span>
                        <span class="font-medium">Historial de Ventas</span>
                    </a>
                    
                    <a href="{{ route('cafe.facturas.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.facturas.*') ? 'bg-amber-700' : 'hover:bg-amber-700' }} transition-colors duration-200" style="color: #451a03 !important;">
                        <span>🧾</span>
                        <span class="font-medium">Facturas</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <h3 class="text-xs uppercase tracking-wider font-semibold" style="color: #451a03 !important;">Inventario</h3>
                    </div>
                    
                    <a href="{{ route('cafe.productos.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.productos.*') ? 'bg-amber-700' : 'hover:bg-amber-700' }} transition-colors duration-200" style="color: #451a03 !important;">
                        <span>☕</span>
                        <span class="font-medium">Productos</span>
                    </a>
                    
                    <a href="{{ route('cafe.inventario.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.inventario.*') ? 'bg-amber-700' : 'hover:bg-amber-700' }} transition-colors duration-200" style="color: #451a03 !important;">
                        <span>📦</span>
                        <span class="font-medium">Control de Stock</span>
                    </a>
                    
                    <a href="{{ route('cafe.inventario.movimientos') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.inventario.movimientos') ? 'bg-amber-700' : 'hover:bg-amber-700' }} transition-colors duration-200" style="color: #451a03 !important;">
                        <span>📈</span>
                        <span class="font-medium">Movimientos</span>
                    </a>
                    
                    <a href="{{ route('cafe.inventario.ajustar') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.inventario.ajustar') ? 'bg-amber-700' : 'hover:bg-amber-700' }} transition-colors duration-200" style="color: #451a03 !important;">
                        <span>⚖️</span>
                        <span class="font-medium">Ajustes</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <h3 class="text-xs uppercase tracking-wider font-semibold" style="color: #451a03 !important;">Compras</h3>
                    </div>
                    
                    <a href="{{ route('cafe.proveedores.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.proveedores.*') ? 'bg-amber-700' : 'hover:bg-amber-700' }} transition-colors duration-200" style="color: #451a03 !important;">
                        <span>🏪</span>
                        <span class="font-medium">Proveedores</span>
                    </a>
                    
                    <a href="{{ route('cafe.compras.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.compras.*') ? 'bg-amber-700' : 'hover:bg-amber-700' }} transition-colors duration-200" style="color: #451a03 !important;">
                        <span>📦</span>
                        <span class="font-medium">Compras</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <h3 class="text-xs uppercase tracking-wider font-semibold" style="color: #451a03 !important;">Clientes</h3>
                    </div>
                    
                    <a href="{{ route('cafe.clientes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.clientes.*') ? 'bg-amber-700' : 'hover:bg-amber-700' }} transition-colors duration-200" style="color: #451a03 !important;">
                        <span>👥</span>
                        <span class="font-medium">Clientes</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <h3 class="text-xs uppercase tracking-wider font-semibold" style="color: #451a03 !important;">Reportes</h3>
                    </div>
                    
                    <a href="{{ route('cafe.reportes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.reportes.*') ? 'bg-amber-700' : 'hover:bg-amber-700' }} transition-colors duration-200" style="color: #451a03 !important;">
                        <span>📊</span>
                        <span class="font-medium">Reportes</span>
                    </a>
                    
                    <a href="{{ route('cafe.inventario.reporte') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.inventario.reporte') ? 'bg-amber-700' : 'hover:bg-amber-700' }} transition-colors duration-200" style="color: #451a03 !important;">
                        <span>📋</span>
                        <span class="font-medium">Inventario</span>
                    </a>
                </nav>
            </div>

            <!-- User Info -->
            <div class="mt-auto p-6 border-t border-amber-700">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-amber-600 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium" style="color: #451a03 !important;">U</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate" style="color: #451a03 !important;">{{ Session::get('user.name', 'Usuario') }}</p>
                        <p class="text-xs truncate" style="color: #78350f !important;">{{ Session::get('user.email', 'admin@scigop.com') }}</p>
                    </div>
                    <a href="{{ route('logout') }}" class="text-amber-900 hover:text-amber-950 transition-colors duration-200 text-lg font-bold" title="Cerrar Sesión" style="color: #78350f !important;">
                        <span>-</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-h-screen bg-orange-50">
            <div class="p-4 md:p-8">
            <!-- Header -->
            <header class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-display font-bold text-amber-800">@yield('title', 'Dashboard')</h1>
                        @hasSection('subtitle')
                            <p class="text-amber-600 mt-1">@yield('subtitle')</p>
                        @endif
                    </div>
                    
                    @hasSection('actions')
                        <div class="flex items-center space-x-4">
                            @yield('actions')
                        </div>
                    @endif
                </div>
            </header>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex">
                        <span class="text-green-600 mr-2">✅</span>
                        <p class="text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex">
                        <span class="text-red-600 mr-2">❌</span>
                        <p class="text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex">
                        <span class="text-green-600 mr-2">✅</span>
                        <p class="text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex">
                        <span class="text-red-600 mr-2">❌</span>
                        <p class="text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

                <!-- Content -->
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    @stack('scripts')
    
</body>
</html>
