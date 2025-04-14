<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementFournisseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'dette_fournisseur_id',
        'date_paiement',
        'montant',
        'mode_paiement',
        'observation'
    ];

    public function dette()
    {
        return $this->belongsTo(DetteFournisseur::class, 'dette_fournisseur_id');
    }
}
