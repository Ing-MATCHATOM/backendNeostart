<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnseignantRequest;
use App\Models\Enseignant;
use App\Models\ParentEnseignant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{

    public function index(){

        $user=request()->user();

        $enseignants=ParentEnseignant::with(['enseignant','parent'])->where('id_parent',$user->id)->get()->map( function($item){
            $item->associations = $item->enseignant->associations()->with(['eleve','temoin'])->get();
            return $item;
        });
        // $enseignants=ParentEnseignant::with(['enseignant','parent','enseignant.associations.eleve','enseignant.associations.temoin'])->where('id_parent',$user->id)->get()->map( function($item){
        //     return $item->enseignant;
        // });
        return response()->json($enseignants);
    }



    public function store(EnseignantRequest $request){
        if(!auth()->check()) {
            return response()->json([
                'message' =>'Utilisateur non authentifié'
            ],401);
        }
        $validate=$request->validated();

        $enseignant = Enseignant::create($validate);

        ParentEnseignant::create([
            'id_parent'=>request()->user()->id,
            'id_enseignant'=>$enseignant->id,
            'mot_de_passe' => Hash::make('password'),
        ]);
        return response()->json('Enrégistrement effectué avec succes');

    }
    public function getStats()
{
    $user = request()->user();

    // On récupère uniquement les enseignants liés à ce parent
    $enseignants = ParentEnseignant::where('id_parent', $user->id)
        ->with('enseignant')
        ->get();

    $total = $enseignants->count();
    
    // ⚠️ Ici je suppose que ton modèle Enseignant a une colonne "status"
    $actifs = $enseignants->where('enseignant.status', 'actif')->count();
    $inactifs = $enseignants->where('enseignant.status', 'inactif')->count();

    return response()->json([
        'total' => $total,
        'actifs' => $actifs,
        'inactifs' => $inactifs
    ]);
}


}

