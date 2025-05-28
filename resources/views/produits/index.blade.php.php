<!-- resources/views/produits/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Liste des Produits</title>
</head>
<body>
    <h1>Liste des Produits</h1>

    <a href="{{ route('produits.create') }}">Ajouter un Produit</a>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produits as $produit)
                <tr>
                    <td>{{ $produit->id }}</td>
                    <td>{{ $produit->nom }}</td>
                    <td>{{ $produit->prix }}</td>
                    <td>{{ $produit->quantite }}</td>
                    <td>
                        <a href="{{ route('produits.show', $produit->id) }}">Voir</a> |
                        <a href="{{ route('produits.edit', $produit->id) }}">Modifier</a> |
                        <form action="{{ route('produits.destroy', $produit->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @extends('layouts.app')

@section('content')
    <h1>Produits</h1>
    <a href="{{ route('produits.create') }}">Ajouter un produit</a>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <table border="1">
        <tr>
            <th>Nom</th><th>Catégorie</th><th>Quantité</th><th>Prix</th><th>Expiration</th><th>Actions</th>
        </tr>
        @foreach($produits as $produit)
            <tr>
                <td>{{ $produit->nom }}</td>
                <td>{{ $produit->categorie }}</td>
                <td>{{ $produit->quantite }}</td>
                <td>{{ $produit->prix }} FCFA</td>
                <td>{{ $produit->date_expiration }}</td>
                <td>
                    <a href="{{ route('produits.edit', $produit) }}">Modifier</a>
                    <form action="{{ route('produits.destroy', $produit) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@endsection

</body>
</html>
