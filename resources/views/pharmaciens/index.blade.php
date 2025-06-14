@extends('layouts.app')

@section('title', 'Gestion des Pharmaciens')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Pharmaciens</h6>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('pharmaciens.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Ajouter un pharmacien
            </a>
            @endif
        </div>
        <div class="card-body">
            @if(count($pharmaciens) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Spécialité</th>
                            <th>Ventes</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pharmaciens as $pharmacien)
                        <tr>
                            <td>{{ $pharmacien->id }}</td>
                            <td>{{ $pharmacien->user->name }}</td>
                            <td>{{ $pharmacien->user->email }}</td>
                            <td>{{ $pharmacien->specialite ?? 'Non spécifiée' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $pharmacien->ventes->count() }} ventes</span>
                                <span class="badge bg-success">{{ number_format($pharmacien->ventes->sum('total'), 2) }} FCFA</span>
                            </td>
                            <td>{{ $pharmacien->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pharmaciens.show', $pharmacien->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('pharmaciens.edit', $pharmacien->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $pharmacien->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                    @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('pharmaciens.update-role', $pharmacien->user_id) }}" method="POST" class="d-flex">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="pharmacien" {{ $pharmacien->user->role === 'pharmacien' ? 'selected' : '' }}>Pharmacien</option>
                                            <option value="admin" {{ $pharmacien->user->role === 'admin' ? 'selected' : '' }}>Administrateur</option>
                                        </select>
                                    </form>
                                    @else
                                    <div class="badge bg-{{ $pharmacien->user->role === 'admin' ? 'success' : 'primary' }} text-uppercase">
                                        {{ $pharmacien->user->role }}
                                    </div>
                                    @endif
                                </div>

                                <!-- Modal de suppression -->
                                <div class="modal fade" id="deleteModal{{ $pharmacien->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $pharmacien->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $pharmacien->id }}">Confirmer la suppression</h5>
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
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <h4>Aucun pharmacien disponible</h4>
                <p>Commencez par ajouter un pharmacien en cliquant sur le bouton ci-dessus.</p>
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
