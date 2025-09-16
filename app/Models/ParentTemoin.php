<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ParentTemoin extends Authenticatable
{
    use HasFactory,HasApiTokens;

    // Nom explicite de la table (si différent de la convention Laravel)
    protected $table = 'parent_temoin';

    // Champs autorisés à être remplis en masse
    protected $fillable = [
        'id_parent',
        'id_temoin',
        'mot_de_passe',
    ];

    /**
     * Relation avec le parent (table inscriptions)
     */
    public function parent()
    {
        return $this->belongsTo(Inscription::class, 'id_parent');
    }

    /**
     * Relation avec le témoin
     */
    public function temoin()
    {
        return $this->belongsTo(Temoin::class, 'id_temoin');
    }
}
