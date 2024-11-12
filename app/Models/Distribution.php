<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'quantity_expected',
        'quantity_received'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);  // Une distribution concerne un produit
    }

    public function user()
    {
        return $this->belongsTo(User::class);  // Une distribution est réalisée par un chef de distribution
    }

    public function counters()
    {
        return $this->belongsToMany(Counter::class)->withPivot('quantity');  // Distribution vers les guichets
    }

    public function partners()
    {
        return $this->belongsToMany(Partner::class)->withPivot('quantity');  // Distribution vers les partenaires
    }
}
