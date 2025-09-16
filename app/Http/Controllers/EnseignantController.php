<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnseignantRequest;
use App\Models\Enseignant;
use App\Models\ParentEnseignant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{
<<<<<<< HEAD
     public function index(){
=======

    public function index(){
>>>>>>> cfc13d0 (commit alorissa)
        $user=request()->user();
        $enseignants=ParentEnseignant::with(['enseignant','parent'])->where('id_parent',$user->id)->get();
        return response()->json($enseignants);
    }
<<<<<<< HEAD

=======
>>>>>>> cfc13d0 (commit alorissa)
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
}
