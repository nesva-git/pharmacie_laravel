<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Affiche le formulaire d'inscription
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    
    /**
     * Affiche le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traite la demande d'inscription
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'required|in:client,pharmacien',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Si l'utilisateur est un pharmacien, créer un profil de pharmacien
        if ($request->role === 'pharmacien') {
            $user->pharmacien()->create([
                'telephone' => $request->telephone ?? null,
                'specialite' => $request->specialite ?? null,
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Inscription réussie.');
    }

    /**
     * Traite la demande de connexion
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Connexion réussie.');
        }

        return back()->withErrors([
            'email' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Déconnecté avec succès.');
    }
    
    /**
     * Affiche le formulaire de profil
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }
    
    /**
     * Met à jour le profil de l'utilisateur
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        
        $user->update($validated);
        
        // Mise à jour du mot de passe si fourni
        if ($request->filled('password') && $request->filled('password_confirmation')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
                'current_password' => 'required|current_password',
            ]);
            
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }
        
        // Mise à jour des informations spécifiques au pharmacien
        if ($user->isPharmacien() && $user->pharmacien) {
            $pharmacienData = $request->validate([
                'telephone' => 'nullable|string|max:20',
                'specialite' => 'nullable|string|max:255',
            ]);
            
            $user->pharmacien->update($pharmacienData);
        }
        
        return redirect()->route('profile')
            ->with('success', 'Profil mis à jour avec succès.');
    }
}
