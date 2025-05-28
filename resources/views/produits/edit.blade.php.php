<!-- resources/views/produits/edit.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Modifier le Produit</title>
</head>
<body>
    <h1>Modifier le Produit</h1>

    <a href="{{ route('produits.index') }}">← Retour à la liste</a>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produits.update', $produit->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nom :</label>
        <input type="text" name="nom" value="{{ old('nom', $produit->nom) }}" required><br><br>

        <label>Prix :</label>
        <input type="number" name="prix" step="0.01" value="{{ old('prix', $produit->prix) }}" required><br><br>

        <label>Quantité :</label>
        <input type="number" name="quantite" value="{{ old('quantite', $produit->quantite) }}" required><br><br>

        <button type="submit">Mettre à jour</button>
    </form>

    @extends('layouts.app')

@section('content')
    <h1>Modifier le produit</h1>
    <form method="POST" action="{{ route('produits.update', $produit) }}">
        @csrf @method('PUT')
        <input name="nom" value="{{ $produit->nom }}" required>
        <input name="categorie" value="{{ $produit->categorie }}" required>
        <input name="quantite" type="number" value="{{ $produit->quantite }}" required>
        <input name="prix" type="number" step="0.01" value="{{ $produit->prix }}" required>
        <input name="date_expiration" type="date" value="{{ $produit->date_expiration }}" required>
        <button type="submit">Mettre à jour</button>
    </form>
@endsection

</body>
</html>
