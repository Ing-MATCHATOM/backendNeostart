<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Seance extends Model
{
    use HasFactory,HasApiTokens;

    protected $table = 'seances';

    protected $fillable = [
        'id_enseignant',
        'id_eleve',
        'id_temoin',
        'id_parent',
        'jour',
        'heure',
        'matiere',
    ];
}
