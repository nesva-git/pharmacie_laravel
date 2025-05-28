@extends('layouts.app')

@section('title', 'Détails du Client')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Détails du Client</h6>
                    <div>
                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-sm">
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
                                            <span class="initials">{{ substr($client->nom, 0, 1) }}</span>
                                        </div>
                                        <h5 class="font-weight-bold">{{ $client->nom }}</h5>
                                        <p class="text-muted">
                                            <i class="fas fa-envelope me-1"></i> {{ $client->email }}
                                        </p>
                                        <div class="badge bg-info">Client</div>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 40%">ID</th>
                                                    <td>{{ $client->id }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email</th>
                                                    <td>{{ $client->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Téléphone</th>
                                                    <td>{{ $client->telephone ?? 'Non spécifié' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Date d'inscription</th>
                                                    <td>{{ $client->created_at->format('d/m/Y') }}</td>
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
                                    <h6 class="m-0 font-weight-bold text-primary">Statistiques des Achats</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-4">
                                            <div class="card bg-primary text-white shadow">
                                                <div class="card-body">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Nombre d'achats</div>
                                                    <div class="h5 mb-0 font-weight-bold">{{ $client->ventes->count() }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="card bg-success text-white shadow">
                                                <div class="card-body">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total des achats</div>
                                                    <div class="h5 mb-0 font-weight-bold">{{ number_format($client->ventes->sum('total'), 2) }} FCFA</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="card bg-info text-white shadow">
                                                <div class="card-body">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Moyenne par achat</div>
                                                    <div class="h5 mb-0 font-weight-bold">
                                                        @if($client->ventes->count() > 0)
                                                            {{ number_format($client->ventes->sum('total') / $client->ventes->count(), 2) }} FCFA
                                                        @else
                                                            0.00 FCFA
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="chart-container" style="position: relative; height:300px;">
                                        <canvas id="achatsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Historique des Achats</h6>
                                    <a href="{{ route('ventes.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-plus me-1"></i> Nouvelle vente
                                    </a>
                                </div>
                                <div class="card-body">
                                    @if($client->ventes->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Produit</th>
                                                        <th>Pharmacien</th>
                                                        <th>Quantité</th>
                                                        <th>Total</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($client->ventes->sortByDesc('date_vente') as $vente)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y H:i') }}</td>
                                                            <td>{{ $vente->produit->nom }}</td>
                                                            <td>
                                                                @if($vente->pharmacien)
                                                                    {{ $vente->pharmacien->user->name }}
                                                                @else
                                                                    <span class="text-muted">Non assigné</span>
                                                                @endif
                                                            </td>
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
                                            <p>Ce client n'a pas encore effectué d'achats.</p>
                                            <a href="{{ route('ventes.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-success">
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
                            <i class="fas fa-trash me-1"></i> Supprimer ce client
                        </button>
                        <div>
                            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
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
                Êtes-vous sûr de vouloir supprimer ce client ?
                <div class="alert alert-info mt-2">
                    <p class="mb-0"><strong>Nom:</strong> {{ $client->nom }}</p>
                    <p class="mb-0"><strong>Email:</strong> {{ $client->email }}</p>
                </div>
                @if($client->ventes->count() > 0)
                <div class="alert alert-warning mt-2">
                    <i class="fas fa-exclamation-triangle me-1"></i> Ce client a {{ $client->ventes->count() }} achats associés. La suppression peut affecter les données existantes.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('clients.destroy', $client->id) }}" method="POST">
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
        background-color: #36b9cc;
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
        const achatsData = @json($achatsParMois);
        
        // Configuration du graphique
        const ctx = document.getElementById('achatsChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: achatsData.map(item => item.mois),
                datasets: [{
                    label: 'Montant des achats (FCFA)',
                    data: achatsData.map(item => item.total),
                    backgroundColor: 'rgba(54, 185, 204, 0.5)',
                    borderColor: 'rgba(54, 185, 204, 1)',
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
