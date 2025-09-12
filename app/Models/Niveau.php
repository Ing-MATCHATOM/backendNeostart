<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    use HasFactory;
    protected $table="niveau";
    protected $fillable=[
        'nom'
    ];

public function eleves(){
    return $this->hasMany(Eleve::class,'niveau_id','id');
}

}
