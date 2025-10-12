@extends('layouts.app')

@section('title','Dashboard')

@section('content')
<h1 class="mb-4">Dashboard</h1>
<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Facturas Pendientes</h5>
                <p class="card-text display-6">12</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Saldo Bancario</h5>
                <p class="card-text display-6">$45,000</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Vencimientos Próximos</h5>
                <p class="card-text display-6">5</p>
            </div>
        </div>
    </div>
</div>
<canvas id="summaryChart" height="100"></canvas>

@push('scripts')
<script>
    const ctx = document.getElementById('summaryChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Ventas', 'Gastos', 'Flujo de Caja'],
            datasets: [{
                label: 'Resumen',
                data: [12000, 8000, 4000],
                backgroundColor: ['#0d6efd','#dc3545','#198754']
            }]
        }
    });
</script>
@endpush
@endsection