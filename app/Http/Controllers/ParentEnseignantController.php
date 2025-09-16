<?php

namespace App\Models;

<<<<<<< HEAD
use App\Models\ParentEnseignant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
>>>>>>> 1ffdeeafabbd68887c97c8fa4b33db42c9495ac9

class ParentEnseignant extends Model
{
<<<<<<< HEAD
    public function associer(Request $request)
    {
        $validated = $request->validate([
            'id_parent' => 'required|integer|exists:parent_eleve,id',
            'id_enseignant' => 'required|integer|exists:enseignants,id',
            'mot_de_passe' => 'nullable|string|min:6'
        ]);

        // Si mot de passe fourni, on le hash
        if (!empty($validated['mot_de_passe'])) {
            $validated['mot_de_passe'] = Hash::make($validated['mot_de_passe']);
        }

        // Création ou mise à jour si déjà existant
        $association = ParentEnseignant::updateOrCreate(
            [
                'id_parent' => $validated['id_parent'],
                'id_enseignant' => $validated['id_enseignant']
            ],
            [
                'mot_de_passe' => $validated['mot_de_passe'] ?? null
            ]
        );

        return response()->json([
            'message' => 'Association créée avec succès',
            'data' => $association
        ], 201);
    }
}

=======
    use HasFactory;
    protected $table =  'parent_enseignant';
    protected $fillable=[
        'id_parent',
        'id_enseignant',
        'mot_de_passe'
    ];

    public function parent(){
        return $this->belongsTo(Inscription::class,'id_parent');
    }

    public function enseignant(){
        return $this->belongsTo(Enseignant::class,'id_enseignant');
    }
}
>>>>>>> 1ffdeeafabbd68887c97c8fa4b33db42c9495ac9
