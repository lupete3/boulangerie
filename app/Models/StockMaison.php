<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StockMaison extends Model
{
    use HasFactory;

    protected $fillable = [
        'designation',
        'unite',
        'prix',
        'solde',
    ];

    public function achatStockMaisons(): HasMany
    {
        return $this->hasMany(AchatStockMaison::class);
    }

    public function mouvementsSorties(): HasMany
    {
        return $this->hasMany(MouvementStockMp::class);
    }

    public function stockUsine(): HasMany
    {
        return $this->hasMany(StockUsine::class);
    }
}
