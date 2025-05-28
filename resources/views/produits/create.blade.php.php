<!-- resources/views/produits/create.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un Produit</title>
</head>
<body>
    <h1>Ajouter un Nouveau Produit</h1>

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

    <form action="{{ route('produits.store') }}" method="POST">
        @csrf
        <label>Nom :</label>
        <input type="text" name="nom" value="{{ old('nom') }}" required><br><br>

        <label>Prix :</label>
        <input type="number" name="prix" step="0.01" value="{{ old('prix') }}" required><br><br>

        <label>Quantité :</label>
        <input type="number" name="quantite" value="{{ old('quantite') }}" required><br><br>

        <button type="submit">Ajouter</button>
    </form>

    @extends('layouts.app')

@section('content')
    <h1>Ajouter un produit</h1>
    <form method="POST" action="{{ route('produits.store') }}">
        @csrf
        <input name="nom" placeholder="Nom" required>
        <input name="categorie" placeholder="Catégorie" required>
        <input name="quantite" type="number" placeholder="Quantité" required>
        <input name="prix" type="number" step="0.01" placeholder="Prix" required>
        <input name="date_expiration" type="date" required>
        <button type="submit">Enregistrer</button>
    </form>
@endsection

</body>
</html>
