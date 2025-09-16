<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Auth};
use Illuminate\Support\Facades\Validator;
use App\Models\Inscription;
use App\Models\Eleve;
use App\Models\Enseignant;
use App\Models\ParentEleve;
use App\Models\ParentEnseignant;
use App\Models\ParentTemoin;
use App\Models\Temoin;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prenom_nom' => 'required|string|max:100',
            'nom_famille' => 'required|string|max:100',
            'courriel' => 'required|email|unique:inscription,courriel',
            'mot_de_passe' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Inscription::create([
            'prenom_nom' => $request->prenom_nom,
            'nom_famille' => $request->nom_famille,
            'courriel' => $request->courriel,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'confirmation' => 0
        ]);

        // Créer un token Sanctum pour l'utilisateur
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
            'token' => $token
        ], 201);
    }

public function login(Request $request)
{
    $request->validate([
        'courriel' => 'required|email',
        'mot_de_passe' => 'required',
        'role' => 'required|in:parent,eleve,temoin,enseignant'
    ]);

    $role = $request->role;
    $user = null;
    $userInfo = null;

    switch ($role) {
        case 'parent':
            $user = Inscription::where('courriel', $request->courriel)->first();
            $userInfo = $user;
            break;

        case 'eleve':
            $eleve = Eleve::where('courriel', $request->courriel)->first();
            if ($eleve) {
                $user = ParentEleve::where('id_eleve', $eleve->id)->first();
                $userInfo = $eleve;
            }
            break;

        case 'enseignant':
            $enseignant = Enseignant::where('courriel', $request->courriel)->first();
            if ($enseignant) {
                $user = ParentEnseignant::where('id_enseignant', $enseignant->id)->first();
                $userInfo = $enseignant;
            }
            break;

        case 'temoin':
            $temoin = Temoin::where('courriel', $request->courriel)->first();
            if ($temoin) {
                $user = ParentTemoin::where('id_temoin', $temoin->id)->first();
                $userInfo = $temoin;
            }
            break;
    }

    if (!$user) {
        return response()->json(['message' => 'Utilisateur non trouvé'], 401);
    }

    // Vérification mot de passe hashé
    if (!Hash::check($request->mot_de_passe, $user->mot_de_passe)) {
        return response()->json(['message' => 'Mot de passe incorrect'], 401);
    }

    // Créer le token Sanctum
    $token = $user->createToken('auth_token')->plainTextToken;

    // Préparer la réponse
    $responseData = [
        'message' => 'Connexion réussie',
        'token'   => $token,
        'role'    => $role,
        'user'    => $userInfo
    ];

    // Ajouter les infos selon le rôle
    switch ($role) {
        case 'eleve': $responseData['parent_eleve'] = $user; break;
        case 'enseignant': $responseData['parent_enseignant'] = $user; break;
        case 'temoin': $responseData['parent_temoin'] = $user; break;
        case 'parent': $responseData['parent_info'] = $user; break;
    }

    return response()->json($responseData);
}



    public function logout(Request $request)
    {
        // Supprimer le token Sanctum de l'utilisateur authentifié
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }

    // Méthode pour obtenir l'utilisateur courant
    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
            'role' => $request->header('X-Role', 'parent') // Vous devrez gérer le rôle dans les headers
        ]);
    }
}