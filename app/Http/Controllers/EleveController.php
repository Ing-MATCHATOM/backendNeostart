<?php

namespace App\Http\Controllers;

use App\Http\Requests\EleveRequest;
use App\Models\Eleve;
use App\Models\ParentEleve;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class EleveController extends Controller
{
    public function store(EleveRequest $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }

        // Validation
        $validated = $request->validated();

        // Création élève
        $eleve = Eleve::create($validated);

        // Mot de passe aléatoire (optionnel)
        $password = Str::random(8);

        // Liaison Parent - Élève
        $parentEleve = ParentEleve::create([
            'id_parent' => $user->id,
            'id_eleve' => $eleve->id,
            'mot_de_passe' => Hash::make($password),
        ]);

        return response()->json([
            'message' => 'Enregistrement d\'élève réussi',
            'eleve' => $eleve,
            'parent_relation' => $parentEleve,
            'password' => $password // ⚠️ seulement si tu veux l’envoyer au frontend
        ], 201);
    }
}
