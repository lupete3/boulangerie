<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function counters()
    {
        return $this->hasMany(Counter::class);  // Un utilisateur peut gérer plusieurs guichets
    }

    public function productions()
    {
        return $this->hasMany(Production::class);  // Un chef de production peut enregistrer plusieurs productions
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class);  // Un chef de distribution peut gérer plusieurs distributions
    }

    public function receptions()
    {
        return $this->hasMany(FinishedProductsReception::class);  // Un chef de dépôt peut recevoir plusieurs produits
    }

    public function transactions()
    {
        return $this->hasMany(CounterTransaction::class);  // Un guichetier peut faire plusieurs transactions
    }












    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id','id');
    }

    public function inventaires(): HasMany
    {
        return $this->hasMany(Cloture::class);
    }

    public function syntheses(): HasMany
    {
        return $this->hasMany(synthese::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
