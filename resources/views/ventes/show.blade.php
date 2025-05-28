@extends('layouts.app')

@section('title', 'Détails de la Vente')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Détails de la Vente #{{ $vente->id }}</h6>
                    <div>
                        <a href="{{ route('ventes.edit', $vente->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <a href="{{ route('ventes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Informations de la Vente</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 40%">ID de la vente</th>
                                                    <td>{{ $vente->id }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Date de vente</th>
                                                    <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y H:i') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Quantité</th>
                                                    <td>{{ $vente->quantite }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total</th>
                                                    <td><span class="badge bg-success">{{ number_format($vente->total, 2) }} FCFA</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Prix unitaire</th>
                                                    <td>{{ number_format($vente->total / $vente->quantite, 2) }} FCFA</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Produit</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            <div class="icon-circle bg-primary">
                                                <i class="fas fa-pills text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="small text-gray-500">Produit ID: {{ $vente->produit->id }}</div>
                                            <span class="font-weight-bold">{{ $vente->produit->nom }}</span>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 40%">Description</th>
                                                    <td>{{ $vente->produit->description }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Prix unitaire</th>
                                                    <td>{{ number_format($vente->produit->prix, 2) }} FCFA</td>
                                                </tr>
                                                <tr>
                                                    <th>Stock actuel</th>
                                                    <td>
                                                        @if($vente->produit->quantite_stock > 10)
                                                            <span class="badge bg-success">{{ $vente->produit->quantite_stock }}</span>
                                                        @elseif($vente->produit->quantite_stock > 0)
                                                            <span class="badge bg-warning">{{ $vente->produit->quantite_stock }}</span>
                                                        @else
                                                            <span class="badge bg-danger">Rupture de stock</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Date d'expiration</th>
                                                    <td>
                                                        @if($vente->produit->date_expiration)
                                                            {{ \Carbon\Carbon::parse($vente->produit->date_expiration)->format('d/m/Y') }}
                                                            @if(\Carbon\Carbon::parse($vente->produit->date_expiration)->isPast())
                                                                <span class="badge bg-danger">Expiré</span>
                                                            @elseif(\Carbon\Carbon::parse($vente->produit->date_expiration)->diffInDays(now()) < 30)
                                                                <span class="badge bg-warning">Expiration proche</span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">Non spécifiée</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <a href="{{ route('produits.show', $vente->produit->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i> Voir le produit
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Client</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            <div class="icon-circle bg-info">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="small text-gray-500">Client ID: {{ $vente->client->id }}</div>
                                            <span class="font-weight-bold">{{ $vente->client->nom }}</span>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 40%">Email</th>
                                                    <td>{{ $vente->client->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Téléphone</th>
                                                    <td>{{ $vente->client->telephone ?: 'Non spécifié' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Nombre d'achats</th>
                                                    <td><span class="badge bg-info">{{ $vente->client->ventes->count() }}</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Total des achats</th>
                                                    <td><span class="badge bg-success">{{ number_format($vente->client->ventes->sum('total'), 2) }} FCFA</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <a href="{{ route('clients.show', $vente->client->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye me-1"></i> Voir le client
                                    </a>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Pharmacien</h6>
                                </div>
                                <div class="card-body">
                                    @if($vente->pharmacien)
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="me-3">
                                                <div class="icon-circle bg-success">
                                                    <i class="fas fa-user-md text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">Pharmacien ID: {{ $vente->pharmacien->id }}</div>
                                                <span class="font-weight-bold">{{ $vente->pharmacien->user->name }}</span>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th style="width: 40%">Email</th>
                                                        <td>{{ $vente->pharmacien->user->email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Nombre de ventes</th>
                                                        <td><span class="badge bg-info">{{ $vente->pharmacien->ventes->count() }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total des ventes</th>
                                                        <td><span class="badge bg-success">{{ number_format($vente->pharmacien->ventes->sum('total'), 2) }} FCFA</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <a href="{{ route('pharmaciens.show', $vente->pharmacien->id) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-eye me-1"></i> Voir le pharmacien
                                        </a>
                                    @else
                                        <div class="text-center py-4">
                                            <div class="icon-circle bg-warning mx-auto mb-3">
                                                <i class="fas fa-exclamation-triangle text-white"></i>
                                            </div>
                                            <p>Aucun pharmacien n'a été assigné à cette vente.</p>
                                            <a href="{{ route('ventes.edit', $vente->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-user-plus me-1"></i> Assigner un pharmacien
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i> Supprimer cette vente
                        </button>
                        <div>
                            <a href="{{ route('ventes.edit', $vente->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                            <a href="{{ route('ventes.index') }}" class="btn btn-secondary">
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
                Êtes-vous sûr de vouloir supprimer cette vente ?
                <div class="alert alert-info mt-2">
                    <p class="mb-0"><strong>Produit:</strong> {{ $vente->produit->nom }}</p>
                    <p class="mb-0"><strong>Client:</strong> {{ $vente->client->nom }}</p>
                    <p class="mb-0"><strong>Date:</strong> {{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y H:i') }}</p>
                    <p class="mb-0"><strong>Montant:</strong> {{ number_format($vente->total, 2) }} FCFA</p>
                </div>
                <div class="alert alert-warning mt-2">
                    <i class="fas fa-info-circle me-1"></i> La suppression de cette vente remettra la quantité ({{ $vente->quantite }}) en stock.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('ventes.destroy', $vente->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
