<?php
// app/Models/EnseignantEleveTemoin.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnseignantEleveTemoin extends Model
{
    protected $table = 'enseignant_eleve_temoin';
    protected $fillable = ['enseignant_id', 'eleve_id', 'temoin_id'];

    public function enseignant() {
        return $this->belongsTo(Enseignant::class);
    }
    public function eleve() {
        return $this->belongsTo(Eleve::class);
    }
    public function temoin() {
        return $this->belongsTo(Temoin::class);
    }
}
