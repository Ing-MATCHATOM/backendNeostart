<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ParentEleve extends Authenticatable
{
    use HasFactory,HasApiTokens;
    protected $table = 'parent_eleve';
    protected $fillable=[
        'id_parent',
        'id_eleve',
        'mot_de_passe'
    ];

    public function parent(){
        return $this->belongsTo(Inscription::class,'id_parent');
    }

    public function eleve(){
        return $this->belongsTo(Eleve::class,'id_eleve');
    }
}
