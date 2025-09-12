<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash,Auth};
use Illuminate\Support\Facades\Validator;
use App\Models\Inscription;

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

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'courriel' => 'required|email',
            'mot_de_passe' => 'required'
        ]);

        $user = Inscription::where('courriel', $request->courriel)->first();

        if (!$user || !Hash::check($request->mot_de_passe, $user->mot_de_passe)) {
            return response()->json([
                'message' => 'Identifiants incorrects'
            ], 401);
        }

        $token= $user->createToken('token')->plainTextToken;
        Auth::login($user);

        // RETOURNEZ BIEN user à la racine de la réponse
        return response()->json([
            'message' => 'Connexion réussie',
            'token'=>$token,
            'user' => $user  // ← Doit être à ce niveau
        ]);

    }

    public function logout(Request $request)
    {
        // Logout simple sans Sanctum
        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }
}