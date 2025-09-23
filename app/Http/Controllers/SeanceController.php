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
        'seances.*.eleve_id' => 'required|integer|exists:eleves,id',
        'seances.*.temoin_id' => 'required|integer|exists:temoins,id', 
        'seances.*.parent_id' => 'required|integer|exists:parents,id',
    ]);

    $enseignantId = auth()->user()->id_enseignant;

    $savedSeances = [];

    foreach ($validated['seances'] as $data) {
        $savedSeances[] = Seance::create([
            'id_enseignant' => $enseignantId,
            'id_eleve' => $data['eleve_id'],
            'id_temoin' => $data['temoin_id'],  
            'id_parent' => $data['parent_id'],
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
    public function indexEleve()
{
    $eleveId = auth()->user()->id_eleve; // ID de l'élève connecté
    if (!$eleveId) {
        return response()->json(['message' => 'Élève non authentifié'], 401);
    }

    $seances = Seance::where('id_eleve', $eleveId)->get(); // 🔹 filtrer ici
    return response()->json($seances);
}

    public function indexTemoin()
{
    $temoinId = auth()->user()->id_temoin; 
    if (!$temoinId) {
        return response()->json(['message' => 'Témoin non authentifié'], 401);
    }

    $seances = Seance::where('id_temoin', $temoinId)->get(); 
    return response()->json($seances);
}

    public function indexParent()
{
    $parentId = auth()->user()->id_parent; 
    if (!$parentId) {
        return response()->json(['message' => 'Parent non authentifié'], 401);
    }

    $seances = Seance::where('id_parent', $parentId)->get(); 
    return response()->json($seances);
}
}
