@extends('layouts.app')

@section('title', 'Nouvelle Vente')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Enregistrer une Nouvelle Vente</h6>
                    <a href="{{ route('ventes.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('ventes.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="produit_id" class="form-label">Produit <span class="text-danger">*</span></label>
                                    <select class="form-select @error('produit_id') is-invalid @enderror" id="produit_id" name="produit_id" required>
                                        <option value="">Sélectionner un produit</option>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit->id }}" data-prix="{{ $produit->prix }}" data-stock="{{ $produit->quantite_stock }}" {{ old('produit_id') == $produit->id ? 'selected' : '' }}>
                                                {{ $produit->nom }} - {{ number_format($produit->prix, 2) }} FCFA (Stock: {{ $produit->quantite_stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('produit_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                    <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                                        <option value="">Sélectionner un client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->nom }} - {{ $client->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="pharmacien_id" class="form-label">Pharmacien</label>
                                    <select class="form-select @error('pharmacien_id') is-invalid @enderror" id="pharmacien_id" name="pharmacien_id">
                                        <option value="">Sélectionner un pharmacien</option>
                                        @foreach($pharmaciens as $pharmacien)
                                            <option value="{{ $pharmacien->id }}" {{ old('pharmacien_id') == $pharmacien->id ? 'selected' : '' }}>
                                                {{ $pharmacien->user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pharmacien_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantite" class="form-label">Quantité <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                                        <input type="number" min="1" class="form-control @error('quantite') is-invalid @enderror" id="quantite" name="quantite" value="{{ old('quantite', 1) }}" required>
                                        @error('quantite')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted" id="stock-info">Stock disponible: -</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="date_vente" class="form-label">Date de vente <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        <input type="datetime-local" class="form-control @error('date_vente') is-invalid @enderror" id="date_vente" name="date_vente" value="{{ old('date_vente', now()->format('Y-m-d\TH:i')) }}" required>
                                        @error('date_vente')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="total" class="form-label">Total <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-euro-sign"></i></span>
                                        <input type="number" step="0.01" min="0" class="form-control @error('total') is-invalid @enderror" id="total" name="total" value="{{ old('total', 0) }}" readonly>
                                        @error('total')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="alert alert-info" id="recap-vente" style="display: none;">
                                    <h6 class="alert-heading">Récapitulatif de la vente</h6>
                                    <div id="recap-content">
                                        <!-- Le contenu sera rempli dynamiquement -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-secondary me-md-2">
                                <i class="fas fa-undo me-1"></i> Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-cash-register me-1"></i> Enregistrer la vente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Fonction pour calculer le total
        function calculerTotal() {
            const produitId = $('#produit_id').val();
            const quantite = parseInt($('#quantite').val()) || 0;
            
            if (produitId && quantite > 0) {
                const prix = parseFloat($('#produit_id option:selected').data('prix')) || 0;
                const stock = parseInt($('#produit_id option:selected').data('stock')) || 0;
                const total = prix * quantite;
                
                $('#total').val(total.toFixed(2));
                $('#stock-info').text(`Stock disponible: ${stock}`);
                
                // Afficher le récapitulatif
                const produitNom = $('#produit_id option:selected').text().split(' - ')[0];
                const clientNom = $('#client_id option:selected').text().split(' - ')[0];
                
                let recapHTML = `
                    <p class="mb-1"><strong>Produit:</strong> ${produitNom}</p>
                    <p class="mb-1"><strong>Prix unitaire:</strong> ${prix.toFixed(2)} FCFA</p>
                    <p class="mb-1"><strong>Quantité:</strong> ${quantite}</p>
                    <p class="mb-1"><strong>Client:</strong> ${clientNom || 'Non sélectionné'}</p>
                    <p class="mb-0"><strong>Total:</strong> ${total.toFixed(2)} FCFA</p>
                `;
                
                $('#recap-content').html(recapHTML);
                $('#recap-vente').show();
                
                // Alerte si quantité > stock
                if (quantite > stock) {
                    $('#stock-info').addClass('text-danger').removeClass('text-muted');
                    $('#stock-info').text(`Attention: Stock insuffisant (${stock} disponible)`);
                } else {
                    $('#stock-info').removeClass('text-danger').addClass('text-muted');
                }
            } else {
                $('#total').val('0.00');
                $('#recap-vente').hide();
            }
        }
        
        // Événements pour recalculer le total
        $('#produit_id, #quantite').on('change input', calculerTotal);
        
        // Initialisation
        calculerTotal();
    });
</script>
@endsection
