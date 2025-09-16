<?php

namespace App\Http\Controllers;

use App\Models\ParentEnseignant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentEnseignantController extends Controller
{
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

