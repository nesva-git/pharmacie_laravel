@extends('layouts.app')

@section('title', 'Modifier un Produit')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Modifier le Produit</h6>
                    <a href="{{ route('produits.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('produits.update', $produit->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom du produit <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom', $produit->nom) }}" required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="prix" class="form-label">Prix <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" class="form-control @error('prix') is-invalid @enderror" id="prix" name="prix" value="{{ old('prix', $produit->prix) }}" required>
                                        <span class="input-group-text">FCFA</span>
                                        @error('prix')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="quantite_stock" class="form-label">Quantité en stock <span class="text-danger">*</span></label>
                                    <input type="number" min="0" class="form-control @error('quantite_stock') is-invalid @enderror" id="quantite_stock" name="quantite_stock" value="{{ old('quantite_stock', $produit->quantite_stock) }}" required>
                                    @error('quantite_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $produit->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="date_expiration" class="form-label">Date d'expiration</label>
                                    <input type="date" class="form-control @error('date_expiration') is-invalid @enderror" id="date_expiration" name="date_expiration" value="{{ old('date_expiration', $produit->date_expiration ? \Carbon\Carbon::parse($produit->date_expiration)->format('Y-m-d') : '') }}">
                                    @error('date_expiration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('produits.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
