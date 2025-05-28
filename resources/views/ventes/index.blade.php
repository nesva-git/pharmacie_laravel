@extends('layouts.app')

@section('title', 'Gestion des Ventes')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Ventes</h6>
            <a href="{{ route('ventes.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Nouvelle vente
            </a>
        </div>
        <div class="card-body">
            @if(count($ventes) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Produit</th>
                            <th>Client</th>
                            <th>Pharmacien</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ventes as $vente)
                        <tr>
                            <td>{{ $vente->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y H:i') }}</td>
                            <td>{{ $vente->produit->nom }}</td>
                            <td>{{ $vente->client->nom }}</td>
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
                                <div class="btn-group" role="group">
                                    <a href="{{ route('ventes.show', $vente->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('ventes.edit', $vente->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $vente->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Modal de suppression -->
                                <div class="modal fade" id="deleteModal{{ $vente->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $vente->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $vente->id }}">Confirmer la suppression</h5>
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
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <h4>Aucune vente disponible</h4>
                <p>Commencez par enregistrer une vente en cliquant sur le bouton ci-dessus.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
            },
            order: [[1, 'desc']] // Trier par date (colonne 1) en ordre décroissant
        });
    });
</script>
@endsection
