<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    use HasFactory;
    protected $table = "eleves";
    protected $fillable = [
        'nom_famille',
        'prenom',
        'courriel',
        'niveau_id'
    ];

    public function parenteleve(){
            return $this->hasMany(ParentEleve::class,'eleve');
        }
    public function niveau(){
        return $this->belongsTo(Niveau::class);
    }

}
