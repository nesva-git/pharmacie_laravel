<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Produit;
use App\Models\Vente;
use App\Models\Pharmacien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord avec les statistiques
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_produits' => Produit::count(),
            'total_clients' => Client::count(),
            'total_pharmaciens' => Pharmacien::count(),
            'total_ventes' => Vente::count(),
            'revenu_total' => Vente::sum('total'),
            'produits_stock_faible' => Produit::where('quantite_stock', '<', 10)->count(),
            'produits_expires' => Produit::whereDate('date_expiration', '<', now())->count(),
        ];

        // Ventes des 7 derniers jours
        $ventesParJour = Vente::select(
            DB::raw('DATE(date_vente) as date'),
            DB::raw('COUNT(*) as nombre'),
            DB::raw('SUM(total) as montant')
        )
            ->whereDate('date_vente', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Produits les plus vendus
        $produitsPopulaires = Vente::select(
            'produit_id',
            DB::raw('SUM(quantite) as quantite_totale'),
            DB::raw('SUM(total) as montant_total')
        )
            ->with('produit:id,nom')
            ->groupBy('produit_id')
            ->orderByDesc('quantite_totale')
            ->limit(5)
            ->get();

        // Ventes par mois pour l'année en cours
        $ventesParMois = Vente::select(
            DB::raw('strftime("%m", date_vente) as mois'),
            DB::raw('SUM(total) as montant')
        )
            ->where(DB::raw('strftime("%Y", date_vente)'), '=', Carbon::now()->year)
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->map(function ($item) {
                $item->mois = (int) $item->mois; // Convertir le mois en entier pour la cohérence
                return $item;
            });

        // Préparer les données pour les graphiques
        $chartData = [
            'ventesParJour' => [
                'labels' => $ventesParJour->pluck('date')->map(function ($date) {
                    return Carbon::parse($date)->format('d/m');
                }),
                'data' => $ventesParJour->pluck('montant'),
            ],
            'produitsPopulaires' => [
                'labels' => $produitsPopulaires->pluck('produit.nom'),
                'data' => $produitsPopulaires->pluck('quantite_totale'),
            ],
            'ventesParMois' => [
                'labels' => $ventesParMois->pluck('mois')->map(function ($mois) {
                    return Carbon::create()->month($mois)->format('F');
                }),
                'data' => $ventesParMois->pluck('montant'),
            ],
        ];

        // Récupérer les dernières ventes
        $dernieresVentes = Vente::with(['produit', 'client', 'pharmacien.user'])
            ->latest('date_vente')
            ->limit(5)
            ->get();

        // Récupérer les produits à faible stock
        $produitsFaibleStock = Produit::where('quantite_stock', '<', 10)
            ->orderBy('quantite_stock')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'chartData',
            'dernieresVentes',
            'produitsFaibleStock'
        ));
    }
}
