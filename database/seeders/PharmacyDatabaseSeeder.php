<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Pharmacien;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PharmacyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Création des utilisateurs
        $this->createUsers();
        
        // Création des clients
        $this->createClients();
        
        // Création des produits
        $this->createProduits();
    }
    
    /**
     * Création des utilisateurs et pharmaciens
     */
    private function createUsers()
    {
        // Utilisateur Admin
        $admin = User::create([
            'name' => 'Admin Système',
            'email' => 'admin@pharmacie.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        
        // Utilisateur Pharmacien 1
        $pharmacien1 = User::create([
            'name' => 'Dr. Moussa Ouedraogo',
            'email' => 'moussa@pharmacie.com',
            'password' => Hash::make('password'),
            'role' => 'pharmacien',
        ]);
        
        // Création du profil pharmacien associé
        Pharmacien::create([
            'user_id' => $pharmacien1->id,
            'specialite' => 'Pharmacologie clinique',
            'telephone' => '+226 70 12 34 56',
            'adresse' => 'Ouagadougou, Burkina Faso',
            'is_active' => true,
        ]);
        
        // Utilisateur Pharmacien 2
        $pharmacien2 = User::create([
            'name' => 'Dr. Aminata Sanogo',
            'email' => 'aminata@pharmacie.com',
            'password' => Hash::make('password'),
            'role' => 'pharmacien',
        ]);
        
        // Création du profil pharmacien associé
        Pharmacien::create([
            'user_id' => $pharmacien2->id,
            'specialite' => 'Pharmacie hospitalière',
            'telephone' => '+226 76 98 76 54',
            'adresse' => 'Bobo-Dioulasso, Burkina Faso',
            'is_active' => true,
        ]);
        
        echo "✓ Utilisateurs et pharmaciens créés avec succès\n";
    }
    
    /**
     * Création des clients
     */
    private function createClients()
    {
        // Client 1
        Client::create([
            'nom' => 'Fatou Diallo',
            'email' => 'fatou.diallo@gmail.com',
            'telephone' => '+226 70 45 67 89',
        ]);
        
        // Client 2
        Client::create([
            'nom' => 'Ibrahim Koné',
            'email' => 'ibrahim.kone@yahoo.fr',
            'telephone' => '+226 76 12 34 56',
        ]);
        
        // Client 3
        Client::create([
            'nom' => 'Aïcha Touré',
            'email' => 'aicha.toure@gmail.com',
            'telephone' => '+226 77 89 01 23',
        ]);
        
        echo "✓ Clients créés avec succès\n";
    }
    
    /**
     * Création des produits
     */
    private function createProduits()
    {
        // Produit 1
        Produit::create([
            'nom' => 'Paracétamol 500mg',
            'description' => 'Analgésique et antipyrétique pour soulager la douleur et réduire la fièvre',
            'prix' => 1500,
            'quantite_stock' => 100,
            'date_expiration' => Carbon::now()->addYears(2),
        ]);
        
        // Produit 2
        Produit::create([
            'nom' => 'Amoxicilline 250mg',
            'description' => 'Antibiotique à large spectre pour traiter diverses infections bactériennes',
            'prix' => 3500,
            'quantite_stock' => 50,
            'date_expiration' => Carbon::now()->addYears(1)->addMonths(6),
        ]);
        
        // Produit 3
        Produit::create([
            'nom' => 'Ibuprofène 400mg',
            'description' => 'Anti-inflammatoire non stéroïdien pour soulager la douleur et l\'inflammation',
            'prix' => 2000,
            'quantite_stock' => 75,
            'date_expiration' => Carbon::now()->addYears(1),
        ]);
        
        // Produit 4
        Produit::create([
            'nom' => 'Vitamine C 1000mg',
            'description' => 'Supplément de vitamine C pour renforcer le système immunitaire',
            'prix' => 4500,
            'quantite_stock' => 60,
            'date_expiration' => Carbon::now()->addYears(3),
        ]);
        
        // Produit 5
        Produit::create([
            'nom' => 'Oméprazole 20mg',
            'description' => 'Inhibiteur de la pompe à protons pour traiter les troubles gastriques',
            'prix' => 5000,
            'quantite_stock' => 40,
            'date_expiration' => Carbon::now()->addYears(2)->addMonths(3),
        ]);
        
        echo "✓ Produits créés avec succès\n";
    }
}
