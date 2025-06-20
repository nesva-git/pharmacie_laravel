@extends('layouts.app')

@section('title', 'Gestion des Produits')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Produits</h6>
            <a href="{{ route('produits.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Ajouter un produit
            </a>
        </div>
        <div class="card-body">
            <!-- Barre de recherche -->
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un produit par nom, description ou prix...">
                </div>
            </div>
            
            @if(count($produits) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Stock</th>
                            <th>Date d'expiration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produits as $produit)
                        <tr>
                            <td>{{ $produit->id }}</td>
                            <td>{{ $produit->nom }}</td>
                            <td>{{ Str::limit($produit->description, 50) }}</td>
                            <td>{{ number_format($produit->prix, 2) }} FCFA</td>
                            <td>
                                <span class="badge bg-{{ $produit->quantite_stock < 10 ? ($produit->quantite_stock < 5 ? 'danger' : 'warning') : 'success' }}">
                                    {{ $produit->quantite_stock }}
                                </span>
                            </td>
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
                                @else
                                    <span class="text-muted">Non définie</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('produits.show', $produit->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $produit->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Modal de suppression -->
                                <div class="modal fade" id="deleteModal{{ $produit->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $produit->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $produit->id }}">Confirmer la suppression</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir supprimer le produit <strong>{{ $produit->nom }}</strong> ?
                                                <br>
                                                <span class="text-danger">Cette action est irréversible.</span>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form action="{{ route('produits.destroy', $produit->id) }}" method="POST" class="d-inline">
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
                <h4>Aucun produit disponible</h4>
                <p>Commencez par ajouter un produit en cliquant sur le bouton ci-dessus.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Fonction de recherche en temps réel
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.querySelector('#dataTable tbody');
        const rows = document.querySelectorAll('#dataTable tbody tr');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                let shouldShow = false;
                
                // Vérifier chaque cellule de la ligne (sauf la dernière colonne d'actions)
                for (let i = 0; i < cells.length - 1; i++) {
                    const cellText = cells[i].textContent.toLowerCase();
                    if (cellText.includes(searchTerm)) {
                        shouldShow = true;
                        break;
                    }
                }
                
                // Afficher ou masquer la ligne selon la recherche
                row.style.display = shouldShow ? '' : 'none';
            });
            
            // Mettre à jour le message si aucun résultat
            const visibleRows = document.querySelectorAll('#dataTable tbody tr[style!="display: none;"]');
            const noResults = document.getElementById('noResults');
            
            if (visibleRows.length === 0 && searchTerm !== '') {
                if (!noResults) {
                    const newRow = document.createElement('tr');
                    newRow.id = 'noResults';
                    newRow.innerHTML = `<td colspan="7" class="text-center py-4">Aucun produit ne correspond à votre recherche.</td>`;
                    tableBody.appendChild(newRow);
                }
            } else if (noResults) {
                noResults.remove();
            }
        });
    });
    $(document).ready(function() {
        $('#dataTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
            }
        });
    });
</script>
@endsection
