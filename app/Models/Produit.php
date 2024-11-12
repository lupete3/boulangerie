<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'nom',
        'prix',
        'kg_par_sac',
        'qte_par_sac',
        'qte_par_kg',
        'solde'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    public function depots()
    {
        return $this->hasMany(Depot::class);
    }

    public function distributionSites()
    {
        return $this->hasMany(DistributionSite::class);
    }

    public function distributionPartenaires()
    {
        return $this->hasMany(DistributionPartenaire::class);
    }




}
