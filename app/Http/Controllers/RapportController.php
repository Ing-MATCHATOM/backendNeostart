<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use App\Models\ParentEnseignant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'parent_id' => 'required',
            'date'        => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin'   => 'required|date_format:H:i|after:heure_debut',
            'contenu'     => 'required|string',
        ]);
        $enseignantId = auth()->user()->id_enseignant;
        $rapport = Rapport::create([
            'id_enseignant' => $enseignantId,
            'date_rapport'  => $validated['date'],
            'id_parent' => $validated['parent_id'],
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

    public function mesRapports(Request $request)
{
    $parentId = $request->user()->id;

$rapports = DB::table('rapport')
    ->join('enseignants', 'enseignants.id', '=', 'rapport.id_enseignant')
    ->join('enseignant_eleve_temoin', 'enseignant_eleve_temoin.enseignant_id', '=', 'rapport.id_enseignant')
    ->join('parent_eleve', 'parent_eleve.id_eleve', '=', 'enseignant_eleve_temoin.eleve_id')
    ->join('eleves', 'eleves.id', '=', 'enseignant_eleve_temoin.eleve_id')
    ->where('parent_eleve.id_parent', $parentId)
    ->select(
        'rapport.id',
        'rapport.date_rapport',
        'rapport.heure_debut',
        'rapport.heure_fin',
        'rapport.contenu',
        'enseignants.nom_famille as enseignant_nom',
        'enseignants.prenom as enseignant_prenom',
        DB::raw("GROUP_CONCAT(CONCAT(eleves.prenom, ' ', eleves.nom_famille) SEPARATOR ', ') as eleves")
    )
    ->groupBy(
        'rapport.id',
        'rapport.date_rapport',
        'rapport.heure_debut',
        'rapport.heure_fin',
        'rapport.contenu',
        'enseignants.nom_famille',
        'enseignants.prenom'
    )
    ->orderBy('rapport.date_rapport', 'desc')
    ->get();


    return response()->json($rapports);
}
public function supprimerRapport($id)
{
    $user = request()->user();

    // Vérifier si le rapport appartient bien à un de ses élèves
    $rapport = DB::table('rapport')
        ->where('id', $id)
        ->first();

    if (!$rapport) {
        return response()->json(['message' => 'Rapport non trouvé'], 404);
    }

    // Supprimer
    DB::table('rapport')->where('id', $id)->delete();

    return response()->json(['message' => 'Rapport supprimé avec succès']);
}


}
