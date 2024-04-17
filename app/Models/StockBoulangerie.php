<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockBoulangerie extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_pf_id',
        'solde'
    ];

    public function mouvementsSorties(): HasMany
    {
        return $this->hasMany(MouvementStockMp::class);
    }

    public function stockProduitFinis(): BelongsTo
    {
        return $this->belongsTo(StockPf::class, 'stock_pf_id', 'id');
    }

    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class);
    }
}
