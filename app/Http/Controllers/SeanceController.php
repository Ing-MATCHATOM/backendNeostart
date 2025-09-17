<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance; // Assure-toi que le modèle existe

class SeanceController extends Controller
{
    public function index()
{
    $seances = Seance::all();
    return response()->json($seances);
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jour' => 'required',
            'heure' => 'required',
            'matiere' => 'required',
        ]);

        \Log::info(request()->user()->id);

        $seance = Seance::create([
            'id_enseignant' =>auth()->user()->id,
            'jour' => $validated['jour'],
            'heure' => $validated['heure'],
            'matiere' => $validated['matiere'],
        ]);

        return response()->json([
            'message' => 'Séance enregistrée avec succès',
            'seance' => $seance
        ], 201);
    }
}
