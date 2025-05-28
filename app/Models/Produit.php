<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nom', 'description', 'prix', 'quantite_stock', 'date_expiration'
    ];
    
    /**
     * Relation avec le modèle Vente
     */
    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }
}
