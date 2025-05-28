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
    $(document).ready(function() {
        $('#dataTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
            }
        });
    });
</script>
@endsection
