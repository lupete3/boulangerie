<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationGuichet extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'user_id',
        'site_id',
        'shift',
        'quantity_trouvee',
        'quantity_recue',
        'quantity_restante',
        'quantity_abimee',
        'quantity_consomme',
        'quantity_dette',
        'quantity_vente',
        'prix',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
