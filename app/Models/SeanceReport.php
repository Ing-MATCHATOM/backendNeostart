<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeanceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'seance_id',
        'nouvelle_date',
        'motif',
        'statut',
    ];

    public function seance()
    {
        return $this->belongsTo(Seance::class, 'seance_id');
    }
}

