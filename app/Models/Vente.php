<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        'designation',
        'quantite',
        'prix',
        'reste',
        'stock_pf_id',
        'observation'
    ];

    public function stockBoulangerie(): BelongsTo
    {
        return $this->belongsTo(StockBoulangerie::class);
    }
}
