<?php
// app/Http/Controllers/ListeController.php
namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\EnseignantEleveTemoin;
use App\Models\Temoin;
use App\Models\Inscription;
use Illuminate\Http\Request;

class AssociationController extends Controller
{
    public function store(Request $request)
    {
        $parent = Inscription::find(auth()->id());

        if (!$parent) {
            return response()->json(['message' => 'Non authentifié.'], 401);
        }

        $validated = $request->validate([
            'enseignant_id' => 'required',
            'eleve_id' => 'required',
            'temoin_id'   => 'nullable'
        ]);

        $associations = EnseignantEleveTemoin::firstOrCreate([
            'enseignant_id' =>$validated['enseignant_id'] ,
            'eleve_id'  => $validated['eleve_id'],
            'temoin_id'   => $validated['temoin_id'],
        ]);

        return response()->json([
            'message' => 'Association enregistrée avec succès',
            'association' => $associations
        ], 201);
    }
    }
