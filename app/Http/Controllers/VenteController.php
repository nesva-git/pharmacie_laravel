<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Produit;
use App\Models\Vente;
use App\Models\Pharmacien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventes = Vente::with(['produit', 'client', 'pharmacien'])->latest('date_vente')->get();
        return view('ventes.index', compact('ventes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $produits = Produit::where('quantite_stock', '>', 0)->get();
        $clients = Client::all();
        $pharmaciens = Pharmacien::with('user')->get();
        return view('ventes.create', compact('produits', 'clients', 'pharmaciens'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'client_id' => 'required|exists:clients,id',
            'quantite' => 'required|integer|min:1',
            'date_vente' => 'required|date',
        ]);
        
        // Récupérer le produit pour vérifier la disponibilité et calculer le total
        $produit = Produit::findOrFail($request->produit_id);
        
        // Vérifier si la quantité demandée est disponible
        if ($produit->quantite_stock < $request->quantite) {
            return back()->withErrors(['quantite' => 'La quantité demandée n\'est pas disponible en stock.'])->withInput();
        }
        
        // Calculer le total
        $total = $produit->prix * $request->quantite;
        
        // Créer la vente
        $vente = new Vente([
            'produit_id' => $request->produit_id,
            'client_id' => $request->client_id,
            'quantite' => $request->quantite,
            'total' => $total,
            'date_vente' => $request->date_vente,
        ]);
        
        // Si l'utilisateur connecté est un pharmacien, associer la vente à ce pharmacien
        if (Auth::check() && Auth::user()->isPharmacien()) {
            $vente->pharmacien_id = Auth::user()->pharmacien->id;
        }
        
        $vente->save();
        
        // Mettre à jour le stock du produit
        $produit->quantite_stock -= $request->quantite;
        $produit->save();
        
        return redirect()->route('ventes.index')
            ->with('success', 'Vente enregistrée avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Vente $vente)
    {
        $vente->load(['produit', 'client', 'pharmacien.user']);
        return view('ventes.show', compact('vente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Vente $vente)
    {
        $produits = Produit::all();
        $clients = Client::all();
        $pharmaciens = Pharmacien::with('user')->get();
        return view('ventes.edit', compact('vente', 'produits', 'clients', 'pharmaciens'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vente $vente)
    {
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'client_id' => 'required|exists:clients,id',
            'quantite' => 'required|integer|min:1',
            'date_vente' => 'required|date',
        ]);
        
        // Si le produit ou la quantité a changé, mettre à jour le stock
        if ($vente->produit_id != $request->produit_id || $vente->quantite != $request->quantite) {
            // Remettre l'ancienne quantité en stock
            $ancienProduit = Produit::findOrFail($vente->produit_id);
            $ancienProduit->quantite_stock += $vente->quantite;
            $ancienProduit->save();
            
            // Vérifier la disponibilité du nouveau produit
            $nouveauProduit = Produit::findOrFail($request->produit_id);
            if ($nouveauProduit->quantite_stock < $request->quantite) {
                return back()->withErrors(['quantite' => 'La quantité demandée n\'est pas disponible en stock.'])->withInput();
            }
            
            // Mettre à jour le stock du nouveau produit
            $nouveauProduit->quantite_stock -= $request->quantite;
            $nouveauProduit->save();
            
            // Calculer le nouveau total
            $total = $nouveauProduit->prix * $request->quantite;
        } else {
            // Si seule la date a changé, garder le même total
            $total = $vente->total;
        }
        
        // Mettre à jour la vente
        $vente->update([
            'produit_id' => $request->produit_id,
            'client_id' => $request->client_id,
            'quantite' => $request->quantite,
            'total' => $total,
            'date_vente' => $request->date_vente,
        ]);
        
        return redirect()->route('ventes.index')
            ->with('success', 'Vente mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vente $vente)
    {
        // Remettre la quantité en stock
        $produit = Produit::findOrFail($vente->produit_id);
        $produit->quantite_stock += $vente->quantite;
        $produit->save();
        
        // Supprimer la vente
        $vente->delete();
        
        return redirect()->route('ventes.index')
            ->with('success', 'Vente supprimée avec succès.');
    }
}
