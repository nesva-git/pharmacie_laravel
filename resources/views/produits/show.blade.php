@extends('layouts.app')

@section('title', 'Détails du Produit')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Détails du Produit</h6>
                    <div>
                        <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <a href="{{ route('produits.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">ID</th>
                                    <td>{{ $produit->id }}</td>
                                </tr>
                                <tr>
                                    <th>Nom</th>
                                    <td>{{ $produit->nom }}</td>
                                </tr>
                                <tr>
                                    <th>Prix</th>
                                    <td>{{ number_format($produit->prix, 2) }} FCFA</td>
                                </tr>
                                <tr>
                                    <th>Quantité en stock</th>
                                    <td>
                                        <span class="badge bg-{{ $produit->quantite_stock < 10 ? ($produit->quantite_stock < 5 ? 'danger' : 'warning') : 'success' }}">
                                            {{ $produit->quantite_stock }}
                                        </span>
                                        @if($produit->quantite_stock < 10)
                                            <span class="text-danger ms-2">
                                                <i class="fas fa-exclamation-triangle"></i> 
                                                Stock faible
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date d'expiration</th>
                                    <td>
                                        @if($produit->date_expiration)
                                            @php
                                                $expiration = \Carbon\Carbon::parse($produit->date_expiration);
                                                $now = \Carbon\Carbon::now();
                                                $isExpired = $expiration->isPast();
                                                $isNearExpiration = $expiration->diffInDays($now) <= 30 && !$isExpired;
                                            @endphp
                                            <span class="badge bg-{{ $isExpired ? 'danger' : ($isNearExpiration ? 'warning' : 'success') }}">
                                                {{ $expiration->format('d/m/Y') }}
                                            </span>
                                            @if($isExpired)
                                                <span class="text-danger ms-2">
                                                    <i class="fas fa-exclamation-circle"></i> 
                                                    Produit expiré
                                                </span>
                                            @elseif($isNearExpiration)
                                                <span class="text-warning ms-2">
                                                    <i class="fas fa-exclamation-triangle"></i> 
                                                    Expiration proche ({{ $expiration->diffInDays($now) }} jours)
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted">Non définie</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date de création</th>
                                    <td>{{ \Carbon\Carbon::parse($produit->created_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Dernière mise à jour</th>
                                    <td>{{ \Carbon\Carbon::parse($produit->updated_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold">Description</h6>
                                </div>
                                <div class="card-body">
                                    @if($produit->description)
                                        {{ $produit->description }}
                                    @else
                                        <p class="text-muted">Aucune description disponible</p>
                                    @endif
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold">Historique des ventes</h6>
                                </div>
                                <div class="card-body">
                                    @if(count($produit->ventes) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Client</th>
                                                        <th>Quantité</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($produit->ventes->sortByDesc('date_vente')->take(5) as $vente)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}</td>
                                                            <td>{{ $vente->client->nom }}</td>
                                                            <td>{{ $vente->quantite }}</td>
                                                            <td>{{ number_format($vente->total, 2) }} FCFA</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if(count($produit->ventes) > 5)
                                            <div class="text-center mt-2">
                                                <a href="{{ route('ventes.index') }}?produit_id={{ $produit->id }}" class="btn btn-sm btn-primary">
                                                    Voir toutes les ventes
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-muted">Aucune vente enregistrée pour ce produit</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
