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

    // Nómina Dashboard
    Route::get('/payroll', [\App\Http\Controllers\PayrollDashboardController::class, 'index'])->name('payroll.dashboard');
    
    // Empleados
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
    
    // Nóminas
    Route::resource('payrolls', \App\Http\Controllers\PayrollController::class);
    Route::post('/payrolls/{payroll}/calculate', [\App\Http\Controllers\PayrollController::class, 'calculate'])->name('payrolls.calculate');
    Route::post('/payrolls/{payroll}/approve', [\App\Http\Controllers\PayrollController::class, 'approve'])->name('payrolls.approve');
    Route::post('/payrolls/{payroll}/pay', [\App\Http\Controllers\PayrollController::class, 'pay'])->name('payrolls.pay');
    
    // Nómina (rutas compatibles)
    Route::prefix('nomina')->name('payroll.')->group(function () {
        Route::get('/', function () {
            return view('nomina.index');
        })->name('index');
        
        // Empleados
        Route::resource('empleados', \App\Http\Controllers\EmployeeController::class)->parameters([
            'empleados' => 'employee'
        ]);
        
        // Nóminas
        Route::resource('nominas', \App\Http\Controllers\PayrollController::class)->parameters([
            'nominas' => 'payroll'
        ]);
        Route::post('/nominas/{payroll}/calcular', [\App\Http\Controllers\PayrollController::class, 'calculate'])->name('nominas.calculate');
        Route::post('/nominas/{payroll}/aprobar', [\App\Http\Controllers\PayrollController::class, 'approve'])->name('nominas.approve');
        Route::post('/nominas/{payroll}/pagar', [\App\Http\Controllers\PayrollController::class, 'pay'])->name('nominas.pay');
    });
    
    // DIAN - Sistema de Información Tributaria
    Route::prefix('dian')->name('dian.')->group(function () {
        Route::get('/', [\App\Http\Controllers\DianController::class, 'index'])->name('index');
        Route::get('/declaraciones', [\App\Http\Controllers\DianController::class, 'declaraciones'])->name('declaraciones');
        Route::get('/retenciones', [\App\Http\Controllers\DianController::class, 'retenciones'])->name('retenciones');
        Route::get('/certificacion', [\App\Http\Controllers\DianController::class, 'certificacion'])->name('certificacion');
        Route::get('/aportes-parafiscales', [\App\Http\Controllers\DianController::class, 'aportes'])->name('aportes');
    });
    
    // Reportes
    Route::prefix('reportes')->name('reports.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('index');
        Route::get('/data', [\App\Http\Controllers\ReportController::class, 'getReportData'])->name('data');
        
        // Reportes PDF
        Route::get('/nomina/pdf', [\App\Http\Controllers\ReportController::class, 'payrollPDF'])->name('payroll.pdf');
        Route::post('/nomina', [\App\Http\Controllers\ReportController::class, 'payrollReport'])->name('payroll');
        Route::post('/ventas', [\App\Http\Controllers\ReportController::class, 'salesReport'])->name('sales');
        Route::post('/inventario', [\App\Http\Controllers\ReportController::class, 'inventoryReport'])->name('inventory');
        Route::get('/dian/certificado/pdf', [\App\Http\Controllers\ReportController::class, 'dianCertificatePDF'])->name('dian.certificate.pdf');
        Route::post('/dian-ingresos', [\App\Http\Controllers\ReportController::class, 'dianIncomeReport'])->name('dian.income');
    });

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
    
    // Inventario
    Route::get('/inventario', [\App\Http\Controllers\Cafe\InventarioController::class, 'index'])->name('inventario.index');
    Route::get('/inventario/movimientos', [\App\Http\Controllers\Cafe\InventarioController::class, 'movimientos'])->name('inventario.movimientos');
    Route::get('/inventario/ajustar', [\App\Http\Controllers\Cafe\InventarioController::class, 'ajustar'])->name('inventario.ajustar');
    Route::post('/inventario/ajustar', [\App\Http\Controllers\Cafe\InventarioController::class, 'procesarAjuste'])->name('inventario.procesar-ajuste');
    Route::get('/inventario/reporte', [\App\Http\Controllers\Cafe\InventarioController::class, 'reporte'])->name('inventario.reporte');
    
    // Proveedores
    Route::resource('proveedores', \App\Http\Controllers\Cafe\ProveedorController::class)->parameters([
        'proveedores' => 'proveedor'
    ]);
    
    // Compras
    Route::resource('compras', \App\Http\Controllers\Cafe\CompraController::class);
    Route::post('/compras/{compra}/recibir', [\App\Http\Controllers\Cafe\CompraController::class, 'recibir'])->name('compras.recibir');
    
    // Sistema POS
    Route::get('/pos', [\App\Http\Controllers\Cafe\POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/procesar', [\App\Http\Controllers\Cafe\POSController::class, 'procesarVenta'])->name('pos.procesar');
    Route::get('/pos/search', [\App\Http\Controllers\Cafe\POSController::class, 'searchProducts'])->name('pos.search');
    Route::get('/pos/factura/{id}', [\App\Http\Controllers\Cafe\POSController::class, 'mostrarFactura'])->name('pos.factura');
});
