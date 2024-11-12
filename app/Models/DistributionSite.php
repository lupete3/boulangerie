<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'site_id',
        'quantity',
        'user_id'
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
