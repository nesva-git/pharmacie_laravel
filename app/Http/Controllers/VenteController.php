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
        $query = Vente::with(['produit', 'client', 'pharmacien']);
        
        // Si l'utilisateur est un pharmacien, ne récupérer que ses ventes
        if (auth()->user()->role === 'pharmacien') {
            $query->where('pharmacien_id', auth()->user()->pharmacien->id);
        }
        
        $ventes = $query->latest('date_vente')->get();
        
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
        
        // Vérifier si l'utilisateur est autorisé à voir cette vente
        if (auth()->user()->role === 'pharmacien' && $vente->pharmacien_id !== auth()->user()->pharmacien->id) {
            abort(403, 'Accès non autorisé à cette vente.');
        }
        
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
        // Vérifier si l'utilisateur est autorisé à modifier cette vente
        if (auth()->user()->role === 'pharmacien' && $vente->pharmacien_id !== auth()->user()->pharmacien->id) {
            abort(403, 'Accès non autorisé à cette vente.');
        }
        
        // Validation des données
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'client_id' => 'required|exists:clients,id',
            'quantite' => 'required|integer|min:1',
            'date_vente' => 'required|date',
            'pharmacien_id' => 'nullable|exists:pharmaciens,id',
        ]);
        
        // Vérifier si l'utilisateur peut modifier le pharmacien
        if (auth()->user()->role === 'pharmacien' && $request->has('pharmacien_id') && $request->pharmacien_id != $vente->pharmacien_id) {
            return back()->withErrors(['pharmacien_id' => 'Vous n\'êtes pas autorisé à modifier le pharmacien.'])->withInput();
        }
        
        // Récupérer le produit
        $produit = Produit::findOrFail($validated['produit_id']);
        
        // Calculer la différence de quantité pour la mise à jour du stock
        $differenceQuantite = $validated['quantite'] - $vente->quantite;
        
        // Vérifier si la nouvelle quantité est disponible en stock
        if ($produit->quantite_stock < $differenceQuantite) {
            return back()->withErrors(['quantite' => 'Stock insuffisant pour cette modification.'])->withInput();
        }
        
        // Démarrer une transaction pour assurer l'intégrité des données
        \DB::beginTransaction();
        
        try {
            // Mettre à jour la vente
            $vente->update([
                'produit_id' => $validated['produit_id'],
                'client_id' => $validated['client_id'],
                'quantite' => $validated['quantite'],
                'total' => $produit->prix * $validated['quantite'],
                'date_vente' => $validated['date_vente'],
                'pharmacien_id' => auth()->user()->role === 'admin' && isset($validated['pharmacien_id']) 
                    ? $validated['pharmacien_id'] 
                    : $vente->pharmacien_id,
            ]);
            
            // Mettre à jour le stock du produit
            $produit->quantite_stock -= $differenceQuantite;
            $produit->save();
            
            // Valider la transaction
            \DB::commit();
            
            return redirect()->route('ventes.index')
                ->with('success', 'Vente mise à jour avec succès.');
                
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            \DB::rollBack();
            
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour de la vente.'])->withInput();
        }
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
