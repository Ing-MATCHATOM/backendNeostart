<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentTemoin extends Model
{
    use HasFactory;
    protected $table =  'parent_temoin';
    protected $fillable=[
        'id_parent',
        'id_temoin',
        'mot_de_passe'
    ];

    public function inscription(){
        return $this->belongsTo(Inscription::class);
    }

    public function temoin(){
        return $this->belongsTo(Temoin::class);
    }
}
