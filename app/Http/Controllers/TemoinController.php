<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemoinRequest;
use App\Models\Temoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ParentTemoin;


class TemoinController extends Controller
{  
    public function index()
    {
        // Retourne tous les eleve en JSON
        $user = request()->user();
        $temoin=ParentTemoin::with(['temoin','parent'])->where('id_parent',$user->id)->get();
        return response()->json($temoin);
    }
 
  public function store(TemoinRequest $request)
{   
   
    
    // Vérifiez si l'utilisateur est authentifié
    if (!auth()->check()) {
        return response()->json([
            'message' => 'Utilisateur non authentifié'
        ], 401);
    }

    $validate = $request->validated();

    $temoin = Temoin::create($validate);

    ParentTemoin::create([
        'id_parent' => request()->user()->id, 
        'id_temoin' => $temoin->id,
        'mot_de_passe' => Hash::make('password'),
    ]);

    return response()->json('Enregistrement reussi');
}
}
