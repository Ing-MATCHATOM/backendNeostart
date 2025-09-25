<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeanceValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_seance',
        'id_individu',
        'type_individu',
        'statut',
    ];
}
