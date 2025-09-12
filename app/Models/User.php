<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 


class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;

    protected $table = 'users';
    
    // DÉSACTIVER LES TIMESTAMPS AUTOMATIQUES
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'email', 
        'password',
      
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected $casts = [
        'cree_le' => 'datetime',
        'confirmation' => 'boolean'
    ];

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }
}