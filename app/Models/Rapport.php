<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    use HasFactory;
    protected $table="rapport";

    protected $fillable = [
        'id_enseignant',
        'date_rapport',
        'heure_debut',
        'heure_fin',
        'contenu',
    ];
}
