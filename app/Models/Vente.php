<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'produit_id', 
        'client_id', 
        'pharmacien_id',
        'quantite', 
        'total', 
        'date_vente'
    ];

    /**
     * Relation avec le modèle Produit
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Relation avec le modèle Client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    /**
     * Relation avec le modèle Pharmacien
     */
    public function pharmacien()
    {
        return $this->belongsTo(Pharmacien::class);
    }
}
