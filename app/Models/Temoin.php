<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temoin extends Model
{
    use HasFactory;
    protected $table = 'temoins';
    protected $fillable=[
        'nom',
        'prenom',
        'courriel'
    ];

    public function parenttemoin(){
        return $this->hasMany(ParentTemoin::class,'temoin');
    }
}
