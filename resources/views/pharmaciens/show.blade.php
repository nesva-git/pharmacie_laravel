@extends('layouts.app')

@section('title', 'Détails du Pharmacien')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Détails du Pharmacien</h6>
                    <div>
                        <a href="{{ route('pharmaciens.edit', $pharmacien->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <a href="{{ route('pharmaciens.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Informations Personnelles</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <div class="avatar-circle mx-auto mb-3">
                                            <span class="initials">{{ substr($pharmacien->user->name, 0, 1) }}</span>
                                        </div>
                                        <h5 class="font-weight-bold">{{ $pharmacien->user->name }}</h5>
                                        <p class="text-muted">
                                            <i class="fas fa-envelope me-1"></i> {{ $pharmacien->user->email }}
                                        </p>
                                        <div class="badge bg-primary">Pharmacien</div>
                                        @if($pharmacien->is_active)
                                            <div class="badge bg-success">Actif</div>
                                        @else
                                            <div class="badge bg-danger">Inactif</div>
                                        @endif
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 40%">ID</th>
                                                    <td>{{ $pharmacien->id }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Spécialité</th>
                                                    <td>{{ $pharmacien->specialite ?? 'Non spécifiée' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Téléphone</th>
                                                    <td>{{ $pharmacien->telephone ?? 'Non spécifié' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Adresse</th>
                                                    <td>{{ $pharmacien->adresse ?? 'Non spécifiée' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Date d'inscription</th>
                                                    <td>{{ $pharmacien->created_at->format('d/m/Y') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Statistiques des Ventes</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-4">
                                            <div class="card bg-primary text-white shadow">
                                                <div class="card-body">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Nombre de ventes</div>
                                                    <div class="h5 mb-0 font-weight-bold">{{ $pharmacien->ventes->count() }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="card bg-success text-white shadow">
                                                <div class="card-body">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total des ventes</div>
                                                    <div class="h5 mb-0 font-weight-bold">{{ number_format($pharmacien->ventes->sum('total'), 2) }} FCFA</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="card bg-info text-white shadow">
                                                <div class="card-body">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Moyenne par vente</div>
                                                    <div class="h5 mb-0 font-weight-bold">
                                                        @if($pharmacien->ventes->count() > 0)
                                                            {{ number_format($pharmacien->ventes->sum('total') / $pharmacien->ventes->count(), 2) }} FCFA
                                                        @else
                                                            0.00 FCFA
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="chart-container" style="position: relative; height:300px;">
                                        <canvas id="ventesChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Dernières Ventes</h6>
                                    <a href="{{ route('ventes.index') }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-list me-1"></i> Voir toutes les ventes
                                    </a>
                                </div>
                                <div class="card-body">
                                    @if($pharmacien->ventes->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Produit</th>
                                                        <th>Client</th>
                                                        <th>Quantité</th>
                                                        <th>Total</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($pharmacien->ventes->sortByDesc('date_vente')->take(5) as $vente)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y H:i') }}</td>
                                                            <td>{{ $vente->produit->nom }}</td>
                                                            <td>{{ $vente->client->nom }}</td>
                                                            <td>{{ $vente->quantite }}</td>
                                                            <td>{{ number_format($vente->total, 2) }} FCFA</td>
                                                            <td>
                                                                <a href="{{ route('ventes.show', $vente->id) }}" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <div class="icon-circle bg-warning mx-auto mb-3">
                                                <i class="fas fa-exclamation-triangle text-white"></i>
                                            </div>
                                            <p>Ce pharmacien n'a pas encore effectué de ventes.</p>
                                            <a href="{{ route('ventes.create') }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus me-1"></i> Enregistrer une vente
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i> Supprimer ce pharmacien
                        </button>
                        <div>
                            <a href="{{ route('pharmaciens.edit', $pharmacien->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                            <a href="{{ route('pharmaciens.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce pharmacien ?
                <div class="alert alert-info mt-2">
                    <p class="mb-0"><strong>Nom:</strong> {{ $pharmacien->user->name }}</p>
                    <p class="mb-0"><strong>Email:</strong> {{ $pharmacien->user->email }}</p>
                </div>
                @if($pharmacien->ventes->count() > 0)
                <div class="alert alert-warning mt-2">
                    <i class="fas fa-exclamation-triangle me-1"></i> Ce pharmacien a {{ $pharmacien->ventes->count() }} ventes associées. La suppression peut affecter les données existantes.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('pharmaciens.destroy', $pharmacien->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .avatar-circle {
        width: 100px;
        height: 100px;
        background-color: #4e73df;
        text-align: center;
        border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
    }

    .initials {
        position: relative;
        top: 25px;
        font-size: 50px;
        line-height: 50px;
        color: #fff;
        font-weight: bold;
    }
    
    .icon-circle {
        height: 2.5rem;
        width: 2.5rem;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Données pour le graphique
        const ventesData = @json($ventesParMois);
        
        // Configuration du graphique
        const ctx = document.getElementById('ventesChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ventesData.map(item => item.mois),
                datasets: [{
                    label: 'Montant des ventes (FCFA)',
                    data: ventesData.map(item => item.total),
                    backgroundColor: 'rgba(78, 115, 223, 0.5)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
    });
</script>
@endsection
