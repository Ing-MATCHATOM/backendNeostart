<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use App\Models\ParentEnseignant;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    // Lister tous les rapports de l'enseignant connecté
    public function index()
    {
        $parentEnseignant = ParentEnseignant::find(auth()->id());

        if (!$parentEnseignant) {
            return response()->json(['message' => 'Non authentifié.'], 401);
        }

        $rapports = Rapport::where('id_enseignant', $parentEnseignant->id_enseignant)
            ->orderBy('date_rapport', 'desc')
            ->get();

        return response()->json($rapports);
    }

    // Enregistrer un nouveau rapport
    public function store(Request $request)
    {
        $parentEnseignant = ParentEnseignant::find(auth()->id());

        if (!$parentEnseignant) {
            return response()->json(['message' => 'Non authentifié.'], 401);
        }

        $validated = $request->validate([
            'date'        => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin'   => 'required|date_format:H:i|after:heure_debut',
            'contenu'     => 'required|string',
        ]);

        $rapport = Rapport::create([
            'id_enseignant' => $parentEnseignant->id_enseignant, // ✅ correction
            'date_rapport'  => $validated['date'],
            'heure_debut'   => $validated['heure_debut'],
            'heure_fin'     => $validated['heure_fin'],
            'contenu'       => $validated['contenu'],
        ]);

        return response()->json([
            'message' => 'Rapport enregistré avec succès',
            'rapport' => $rapport
        ], 201);
    }

    // Voir un rapport en détail
    public function show($id)
    {
        $parentEnseignant = ParentEnseignant::find(auth()->id());

        if (!$parentEnseignant) {
            return response()->json(['message' => 'Non authentifié.'], 401);
        }

        $rapport = Rapport::where('id', $id)
            ->where('id_enseignant', $parentEnseignant->id_enseignant) // ✅ correction
            ->firstOrFail();

        return response()->json($rapport);
    }
}
