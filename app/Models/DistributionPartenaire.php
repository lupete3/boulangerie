<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionPartenaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'partenaire_id',
        'quantity',
        'user_id'
    ];
    
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function partenaire()
    {
        return $this->belongsTo(Partenaire::class);
    }
}
