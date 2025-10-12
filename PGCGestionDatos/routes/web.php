<?php

use Illuminate\Support\Facades\Route;

// Rutas de autenticación
Route::get('/', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.attempt');
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.attempt');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Rutas protegidas (middleware auth simple basado en sesión)
Route::middleware('session.auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Usuarios
    Route::get('/usuarios', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/usuarios/crear', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');

    // Roles
    Route::get('/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('roles.index');

    // Contabilidad
    Route::get('/transacciones/crear', [App\Http\Controllers\TransactionController::class, 'create'])->name('transactions.create');
    Route::get('/libro-diario', [App\Http\Controllers\LedgerController::class, 'daily'])->name('ledger.daily');
    Route::get('/libro-mayor', [App\Http\Controllers\LedgerController::class, 'general'])->name('ledger.general');
    Route::get('/balances', [App\Http\Controllers\LedgerController::class, 'balances'])->name('balances.index');

    // Facturación
    Route::get('/facturas', [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/facturas/crear', [App\Http\Controllers\InvoiceController::class, 'create'])->name('invoices.create');
    Route::get('/cobros', [App\Http\Controllers\CollectionController::class, 'index'])->name('collections.index');
    Route::get('/cuentas-por-cobrar', [App\Http\Controllers\AccountsReceivableController::class, 'index'])->name('ar.status');

    // Configuración General
    Route::get('/configuracion-general', function () {
        return view('general.index');
    })->name('general.index');

    // Contabilidad
    Route::get('/contabilidad', function () {
        return view('contabilidad.index');
    })->name('accounting.index');

    // Ventas
    Route::get('/ventas', function () {
        return view('ventas.index');
    })->name('sales.index');

    // Compras
    Route::get('/compras', function () {
        return view('compras.index');
    })->name('purchases.index');

    // Impuestos
    Route::get('/impuestos', function () {
        return view('impuestos.index');
    })->name('taxes.index');

    // Nómina
    Route::get('/nomina', function () {
        return view('nomina.index');
    })->name('payroll.index');

    // Inventario
    Route::get('/inventario', function () {
        return view('inventario.index');
    })->name('inventory.index');

    // Auditoría
    Route::get('/auditoria', function () {
        return view('auditoria.index');
    })->name('audit.index');
});

// Rutas del sistema de cafetería (sin middleware por ahora)
Route::prefix('cafe')->name('cafe.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Cafe\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\Cafe\DashboardController::class, 'index']);
    
    // Productos
    Route::resource('productos', \App\Http\Controllers\Cafe\ProductoController::class);
    Route::get('/productos/search/ajax', [\App\Http\Controllers\Cafe\ProductoController::class, 'search'])->name('productos.search');
    
    // Clientes
    Route::resource('clientes', \App\Http\Controllers\Cafe\ClienteController::class);
    
    // Ventas y Facturas
    Route::resource('ventas', \App\Http\Controllers\Cafe\VentaController::class);
    Route::get('/facturas', [\App\Http\Controllers\Cafe\VentaController::class, 'facturas'])->name('facturas.index');
    Route::get('/facturas/{factura}', [\App\Http\Controllers\Cafe\VentaController::class, 'showFactura'])->name('facturas.show');
    
    // Reportes
    Route::get('/reportes', [\App\Http\Controllers\Cafe\ReporteController::class, 'index'])->name('reportes.index');
});
