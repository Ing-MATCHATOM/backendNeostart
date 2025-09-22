<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emploi extends Model
{
    use HasFactory;
    protected $table='seances';
    protected $fillable=[
        'jour',
        'heure',
        'heure_fin',
        'matiere'	
    ];
}
