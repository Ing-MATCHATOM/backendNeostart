<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use Illuminate\Http\Request;
use App\Models\Seance;

class SeanceController extends Controller
{
    // Récupérer toutes les séances
    public function index()
    {
        $seances = Seance::all();
        return response()->json($seances);
    }

    // Enregistrer une ou plusieurs séances
    public function store(Request $request)
    {
        $validated = $request->validate([
            'seances' => 'required|array',
            'seances.*.jour' => 'required|string',
            'seances.*.heure' => 'required|string',
            'seances.*.matiere' => 'required|string',
        ]);

        $enseignantId = auth()->user()->id_enseignant;

        $savedSeances = [];

        foreach ($validated['seances'] as $data) {
            $savedSeances[] = Seance::create([
                'id_enseignant' => $enseignantId,
                'jour' => $data['jour'],
                'heure' => $data['heure'],
                'matiere' => $data['matiere'],
            ]);
        }

        return response()->json([
            'message' => 'Séances enregistrées avec succès',
            'seances' => $savedSeances,
        ], 201);
    }

}
