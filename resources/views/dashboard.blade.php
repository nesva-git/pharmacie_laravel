@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="container-fluid">
    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Produits</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_produits'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pills fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ventes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_ventes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Clients</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_clients'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Revenu Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['revenu_total'], 2) }} FCFA</div>
                        </div>
                        <div class="col-auto">
                            <h1 class="font-weight-bold">XOF</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Alertes</h6>
                </div>
                <div class="card-body">
                    @if($stats['produits_stock_faible'] > 0)
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>{{ $stats['produits_stock_faible'] }}</strong> produits ont un stock faible (moins de 10 unités).
                    </div>
                    @endif
                    
                    @if($stats['produits_expires'] > 0)
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>{{ $stats['produits_expires'] }}</strong> produits sont expirés.
                    </div>
                    @endif
                    
                    @if($stats['produits_stock_faible'] == 0 && $stats['produits_expires'] == 0)
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        Aucune alerte à signaler.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Produits à faible stock</h6>
                </div>
                <div class="card-body">
                    @if(count($produitsFaibleStock) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produitsFaibleStock as $produit)
                                <tr>
                                    <td>{{ $produit->nom }}</td>
                                    <td>
                                        <span class="badge bg-{{ $produit->quantite_stock < 5 ? 'danger' : 'warning' }}">{{ $produit->quantite_stock }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center">Aucun produit à faible stock.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ventes des 7 derniers jours</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="ventesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Produits les plus vendus</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="produitsPopulairesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières ventes -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Dernières ventes</h6>
                    <a href="{{ route('ventes.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-list me-1"></i> Voir toutes les ventes
                    </a>
                </div>
                <div class="card-body">
                    @if(count($dernieresVentes) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Produit</th>
                                    <th>Client</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dernieresVentes as $vente)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $vente->produit->nom }}</td>
                                    <td>{{ $vente->client->nom }}</td>
                                    <td>{{ $vente->quantite }}</td>
                                    <td>{{ number_format($vente->total, 2) }} FCFA</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center">Aucune vente enregistrée.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique des ventes des 7 derniers jours
        const ventesCtx = document.getElementById('ventesChart').getContext('2d');
        const ventesChart = new Chart(ventesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['ventesParJour']['labels']) !!},
                datasets: [{
                    label: 'Montant des ventes (FCFA)',
                    data: {!! json_encode($chartData['ventesParJour']['data']) !!},
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' FCFA';
                            }
                        }
                    }
                }
            }
        });

        // Graphique des produits les plus vendus
        const produitsCtx = document.getElementById('produitsPopulairesChart').getContext('2d');
        const produitsChart = new Chart(produitsCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartData['produitsPopulaires']['labels']) !!},
                datasets: [{
                    data: {!! json_encode($chartData['produitsPopulaires']['data']) !!},
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'
                    ],
                    hoverBackgroundColor: [
                        '#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'
                    ],
                    hoverBorderColor: 'rgba(234, 236, 244, 1)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection
