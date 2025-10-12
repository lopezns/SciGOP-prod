<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SciGOP - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-orange-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-8 h-8 bg-orange-200 rounded-lg flex items-center justify-center">
                        <span class="text-amber-800 font-bold text-lg">☕</span>
                    </div>
                    <h1 class="font-display text-xl font-bold text-orange-100">SciGOP</h1>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('cafe.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.dashboard') ? 'bg-amber-700 text-orange-100' : 'text-orange-300 hover:bg-amber-700 hover:text-orange-100' }} transition-colors duration-200">
                        <span>📊</span>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    
                    <div class="pt-4 pb-2">
                        <h3 class="text-xs uppercase tracking-wider text-orange-400 font-semibold">Inventario</h3>
                    </div>
                    
                    <a href="{{ route('cafe.productos.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.productos.*') ? 'bg-amber-700 text-orange-100' : 'text-orange-300 hover:bg-amber-700 hover:text-orange-100' }} transition-colors duration-200">
                        <span>☕</span>
                        <span class="font-medium">Productos</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <h3 class="text-xs uppercase tracking-wider text-orange-400 font-semibold">Ventas</h3>
                    </div>
                    
                    <a href="{{ route('cafe.ventas.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.ventas.create') ? 'bg-amber-700 text-orange-100' : 'text-orange-300 hover:bg-amber-700 hover:text-orange-100' }} transition-colors duration-200">
                        <span>🛒</span>
                        <span class="font-medium">Nueva Venta</span>
                    </a>
                    
                    <a href="{{ route('cafe.facturas.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.facturas.*') ? 'bg-amber-700 text-orange-100' : 'text-orange-300 hover:bg-amber-700 hover:text-orange-100' }} transition-colors duration-200">
                        <span>🧾</span>
                        <span class="font-medium">Facturas</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <h3 class="text-xs uppercase tracking-wider text-orange-400 font-semibold">Clientes</h3>
                    </div>
                    
                    <a href="{{ route('cafe.clientes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.clientes.*') ? 'bg-amber-700 text-orange-100' : 'text-orange-300 hover:bg-amber-700 hover:text-orange-100' }} transition-colors duration-200">
                        <span>👥</span>
                        <span class="font-medium">Clientes</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <h3 class="text-xs uppercase tracking-wider text-orange-400 font-semibold">Reportes</h3>
                    </div>
                    
                    <a href="{{ route('cafe.reportes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('cafe.reportes.*') ? 'bg-amber-700 text-orange-100' : 'text-orange-300 hover:bg-amber-700 hover:text-orange-100' }} transition-colors duration-200">
                        <span>📈</span>
                        <span class="font-medium">Reportes</span>
                    </a>
                </nav>
            </div>

            <!-- User Info -->
            <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-amber-700">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-amber-600 rounded-full flex items-center justify-center">
                        <span class="text-orange-100 text-sm font-medium">U</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-orange-100 truncate">{{ Session::get('user.name', 'Usuario') }}</p>
                        <p class="text-xs text-orange-400 truncate">{{ Session::get('user.email', 'admin@scigop.com') }}</p>
                    </div>
                    <a href="{{ route('logout') }}" class="text-orange-300 hover:text-orange-100 transition-colors duration-200" title="Cerrar Sesión">
                        <span>🚪</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 main-content">
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

            <!-- Content -->
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
