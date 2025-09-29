<?php

namespace App\Http\Controllers;

use App\Http\Requests\EleveRequest;
use App\Mail\SendPasswordMail;
use App\Models\Eleve;
use App\Models\ParentEleve;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EleveController extends Controller
{
    public function index()
    {
        // Retourne tous les eleve en JSON
        $user = request()->user();
        $eleve=ParentEleve::with(['eleve','parent'])->where('id_parent',$user->id)->get();
        return response()->json($eleve);
    }
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

    // Mot de passe aléatoire
    $password = Str::random(8);

    // Liaison Parent - Élève
    $parentEleve = ParentEleve::create([
        'id_parent'   => $user->id,
        'id_eleve'    => $eleve->id,
        'mot_de_passe'=> Hash::make($password),
    ]);

    // Récupération email élève
    $owner = $eleve->courriel;

    // Envoi du mail
    Mail::to($owner)->send(new SendPasswordMail($user, $eleve, $password));

    return response()->json([
        'message' => 'Enregistrement d\'élève réussi',
        'eleve' => $eleve,
        'parent_relation' => $parentEleve,
        'password' => $password  
    ], 201);
}


    public function getParentEleve(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }

        // $eleves = Eleve::whereHas('parenteleve', function ($query) use ($user) {
        //     $query->where('id_parent', $user->id);
        // })->get();
         $eleves = ParentEleve::where('id_parent', $user->id)->with('eleve')->get();
        return response()->json($eleves);
    }
    
}
