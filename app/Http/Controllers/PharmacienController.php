<?php

namespace App\Http\Controllers;

use App\Models\Pharmacien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PharmacienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pharmaciens = Pharmacien::with('user')->get();
        return view('pharmaciens.index', compact('pharmaciens'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pharmaciens.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'specialite' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
        ]);

        // Créer un utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pharmacien',
        ]);

        // Créer un pharmacien associé à l'utilisateur
        Pharmacien::create([
            'user_id' => $user->id,
            'specialite' => $request->specialite,
            'telephone' => $request->telephone,
        ]);

        return redirect()->route('pharmaciens.index')
            ->with('success', 'Pharmacien ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pharmacien  $pharmacien
     * @return \Illuminate\Http\Response
     */
    public function show(Pharmacien $pharmacien)
    {
        $pharmacien->load(['user', 'ventes']);
        
        // Récupérer les ventes des 6 derniers mois
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        
        // Initialiser un tableau pour stocker les ventes par mois
        $ventesParMois = collect(range(0, 5))->map(function ($i) use ($pharmacien) {
            $date = now()->subMonths(5 - $i);
            $month = $date->month;
            $year = $date->year;
            
            $total = $pharmacien->ventes()
                ->whereYear('date_vente', $year)
                ->whereMonth('date_vente', $month)
                ->sum('total');
                
            return [
                'mois' => $date->translatedFormat('M Y'),
                'total' => $total
            ];
        });
        
        return view('pharmaciens.show', [
            'pharmacien' => $pharmacien,
            'ventesParMois' => $ventesParMois
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pharmacien  $pharmacien
     * @return \Illuminate\Http\Response
     */
    public function edit(Pharmacien $pharmacien)
    {
        $pharmacien->load('user');
        return view('pharmaciens.edit', compact('pharmacien'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pharmacien  $pharmacien
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pharmacien $pharmacien)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $pharmacien->user_id,
            'specialite' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
        ]);

        // Mettre à jour l'utilisateur
        $pharmacien->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Mettre à jour le mot de passe si fourni
        if ($request->filled('password') && $request->filled('password_confirmation')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            $pharmacien->user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Mettre à jour le pharmacien
        $pharmacien->update([
            'specialite' => $request->specialite,
            'telephone' => $request->telephone,
        ]);

        return redirect()->route('pharmaciens.index')
            ->with('success', 'Pharmacien mis à jour avec succès.');
    }

    /**
     * Update the specified user's role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function updateRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|in:admin,pharmacien',
        ]);

        $user = User::findOrFail($userId);
        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'Le rôle a été mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pharmacien  $pharmacien
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pharmacien $pharmacien)
    {
        // Supprimer l'utilisateur associé (cascade supprimera aussi le pharmacien)
        $pharmacien->user->delete();
        
        return redirect()->route('pharmaciens.index')
            ->with('success', 'Pharmacien supprimé avec succès.');
    }
}
