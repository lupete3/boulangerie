<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cloture extends Model
{
    use HasFactory;

    protected $fillable = [
        'qnte_entree',
        'qnte_sortie',
        'avarie',
        'solde',
        'stock_pf_id',
        'site_id',
        'user_id',
    ];
}
