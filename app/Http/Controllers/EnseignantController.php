<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnseignantRequest;
use App\Mail\SendPasswordMail;
use App\Mail\SendPasswordProf;
use App\Models\Enseignant;
use App\Models\ParentEnseignant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

public function mesEleves(Request $request)
{
    $enseignantId = auth()->user()->id_enseignant;

    if (!$enseignantId) {
        return response()->json(['message' => 'Utilisateur non authentifié'], 401);
    }

    // Requête : élèves, témoins et parent
    $associations = DB::table('enseignant_eleve_temoin as eet')
        ->join('eleves', 'eleves.id', '=', 'eet.eleve_id')
        ->leftJoin('temoins', 'temoins.id', '=', 'eet.temoin_id')
        ->join('parent_enseignant as pe', 'pe.id_enseignant', '=', 'eet.enseignant_id')
        ->join('inscription', 'inscription.id', '=', 'pe.id_parent')
        ->where('eet.enseignant_id', $enseignantId)
        ->select(
            'eet.enseignant_id',
            'eleves.id as eleve_id',
            'eleves.nom_famille as eleve_nom',
            'eleves.prenom as eleve_prenom',
            'temoins.id as temoin_id',
            'temoins.nom as temoin_nom',
            'temoins.prenom as temoin_prenom',
            'inscription.id as parent_id',
            'inscription.nom_famille as parent_nom',
            'inscription.prenom_nom as parent_prenom'
        )
        ->get();

    // Liste unique des élèves
    $eleves = $associations
        ->where('eleve_id', '!=', null)
        ->unique('eleve_id')
        ->values();

    // Liste unique des témoins
    $temoins = $associations
        ->where('temoin_id', '!=', null)
        ->unique('temoin_id')
        ->values();

    // Infos parent (unique)
    $parent = $associations->first() ? [
        'parent_id' => $associations->first()->parent_id,
        'parent_nom' => $associations->first()->parent_nom,
        'parent_prenom' => $associations->first()->parent_prenom
    ] : null;

    return response()->json([
        'enseignant_id' => $enseignantId,
        'parent' => $parent,
        'eleves' => $eleves,
        'temoins' => $temoins
    ]);
}




    public function store(EnseignantRequest $request){
        
        $user=request()->user();
        $validate=$request->validated();

        $enseignant = Enseignant::create($validate);

        $password = Str::random(8);

        ParentEnseignant::create([
            'id_parent'=>request()->user()->id,
            'id_enseignant'=>$enseignant->id,
            'mot_de_passe' => Hash::make($password),
        ]);

        $owner = $enseignant->courriel;

        Mail::to($owner)->send(new SendPasswordProf($user, $enseignant, $password));

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

