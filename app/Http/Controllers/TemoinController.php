<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemoinRequest;
use App\Mail\SendPasswordTemoin;
use App\Models\Temoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ParentTemoin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
    
    $user=request()->user();
    $validate = $request->validated();

    $temoin = Temoin::create($validate);
    $password = Str::random(8);

    $owner = $temoin->courriel;

    Mail::to($owner)->send(new SendPasswordTemoin($user, $temoin, $password));

    ParentTemoin::create([
        'id_parent' => request()->user()->id,
        'id_temoin' => $temoin->id,
        'mot_de_passe' => Hash::make('password'),
    ]);

    return response()->json('Enregistrement reussi');
}

public function getParentTemoin(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }

        // $temoins = Temoin::whereHas('parenttemoin', function ($query) use ($user) {
        //     $query->where('id_parent', $user->id);
        // })->get();
         $temoins = ParentTemoin::where('id_parent', $user->id)->with('temoin')->get();
        return response()->json($temoins);
    }
}
