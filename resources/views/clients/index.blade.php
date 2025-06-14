@extends('layouts.app')

@section('title', 'Gestion des Clients')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Clients</h6>
            <a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Ajouter un client
            </a>
        </div>
        <div class="card-body">
            <!-- Barre de recherche -->
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchClientInput" class="form-control" placeholder="Rechercher un client par nom, email ou téléphone...">
                </div>
            </div>
            
            @if(count($clients) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Nombre d'achats</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->nom }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->telephone ?? 'Non renseigné' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $client->ventes->count() }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $client->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Modal de suppression -->
                                <div class="modal fade" id="deleteModal{{ $client->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $client->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $client->id }}">Confirmer la suppression</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir supprimer le client <strong>{{ $client->nom }}</strong> ?
                                                @if($client->ventes->count() > 0)
                                                <div class="alert alert-warning mt-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i> Ce client a {{ $client->ventes->count() }} ventes associées. La suppression peut affecter les données de vente.
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
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <h4>Aucun client disponible</h4>
                <p>Commencez par ajouter un client en cliquant sur le bouton ci-dessus.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Fonction de recherche en temps réel pour les clients
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchClientInput');
        const tableBody = document.querySelector('#dataTable tbody');
        const rows = document.querySelectorAll('#dataTable tbody tr');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let anyVisible = false;
            
            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                let shouldShow = false;
                
                // Vérifier chaque cellule de la ligne (sauf la dernière colonne d'actions)
                for (let i = 0; i < cells.length - 1; i++) {
                    const cellText = cells[i].textContent.toLowerCase();
                    if (cellText.includes(searchTerm)) {
                        shouldShow = true;
                        anyVisible = true;
                        break;
                    }
                }
                
                // Afficher ou masquer la ligne selon la recherche
                row.style.display = shouldShow ? '' : 'none';
            });
            
            // Mettre à jour le message si aucun résultat
            const noResults = document.getElementById('noResults');
            
            if (!anyVisible && searchTerm !== '') {
                if (!noResults) {
                    const newRow = document.createElement('tr');
                    newRow.id = 'noResults';
                    newRow.innerHTML = `<td colspan="6" class="text-center py-4">Aucun client ne correspond à votre recherche.</td>`;
                    tableBody.appendChild(newRow);
                }
            } else if (noResults) {
                noResults.remove();
            }
        });
    });

    // Initialisation de DataTables
    $(document).ready(function() {
        $('#dataTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
            }
        });
    });
</script>
@endsection
