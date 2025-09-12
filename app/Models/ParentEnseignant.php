<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentEnseignant extends Model
{
    use HasFactory;
    protected $table =  'parent_enseignant';
    protected $fillable=[
        'id_parent',
        'id_enseignant',
        'mot_de_passe'
    ];

    public function inscription(){
        return $this->belongsTo(Inscription::class);
    }

    public function enseignant(){
        return $this->belongsTo(Enseignant::class);
    }
}

