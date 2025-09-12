<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens; 


class Inscription extends Authenticatable
{
    use HasFactory,HasApiTokens;

    protected $table = 'inscription'; // ta table
    protected $primaryKey = 'id';
    public $timestamps = false; // car tu utilises `cree_le` et pas `created_at`

    protected $fillable = [
        'prenom_nom',
        'nom_famille',
        'courriel',
        'mot_de_passe',
        'confirmation'
    ];

    public function parenttemoin(){
        return $this->hasMany(ParentTemoin::class,'parent');
    }
}
