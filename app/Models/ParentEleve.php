<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentEleve extends Model
{
    use HasFactory;
    protected $table = 'parent_eleve';
    protected $fillable=[
        'id_parent',
        'id_eleve',
        'mot_de_passe'
    ];

    public function inscription(){
        return $this->belongsTo(Inscription::class);
    }

    public function eleve(){
        return $this->belongsTo(Eleve::class);
    }
}
