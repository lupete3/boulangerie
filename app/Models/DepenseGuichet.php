<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepenseGuichet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'site_id',
        'shift',
        'montant',
        'motif'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
