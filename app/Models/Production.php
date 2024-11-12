<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'user_id',
        'quantity_demande',
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);  // Une production est associée à un produit
    }

    public function user()
    {
        return $this->belongsTo(User::class);  // Une production est réalisée par un chef de production
    }






    public function produitFinis(): BelongsTo
    {
        return $this->belongsTo(StockPf::class, 'stock_pf_id', 'id');
    }

    public function compositions(): HasMany
    {
        return $this->hasMany(Composition::class);
    }
}
