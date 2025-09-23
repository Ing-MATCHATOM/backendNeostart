<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Rapport extends Model
{
    use HasFactory,HasApiTokens;
    protected $table="rapport";
    protected $fillable = [
        'id_enseignant',
        'id_parent',
        'date_rapport',
        'heure_debut',
        'heure_fin',
        'contenu',
    ];

    protected $guarded=['id_rapport'];
        
}