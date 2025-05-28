<!DOCTYPE html>
<html>
<head>
    <title>Détails du produit</title>
</head>
<body>
    <h1>Détails du produit</h1>

    <p><strong>Nom :</strong> {{ $produit->nom }}</p>
    <p><strong>Prix :</strong> {{ $produit->prix }} F CFA</p>
    <p><strong>Quantité :</strong> {{ $produit->quantite }}</p>

    <a href="{{ route('produits.edit', $produit->id) }}">Modifier</a> |
    <a href="{{ route('produits.index') }}">← Retour à la liste</a>
</body>
</html>
