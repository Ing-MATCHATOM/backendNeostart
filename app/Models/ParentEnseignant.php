<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ParentEnseignant extends Authenticatable
{
    use HasFactory,HasApiTokens;
    protected $table =  'parent_enseignant';
    protected $fillable=[
        'id_parent',
        'id_enseignant',
        'mot_de_passe'
    ];

    public function inscription(){
        return $this->belongsTo(Inscription::class,'id_parent');
    }

    public function enseignant(){
        return $this->belongsTo(Enseignant::class,'id_enseignant');
    }
}

