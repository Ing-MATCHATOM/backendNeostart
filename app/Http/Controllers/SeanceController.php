<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\SeanceValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        'seances.*.temoin_id' => 'nullable', 
        'seances.*.parent_id' => 'required|integer|exists:inscription,id',
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
    $user=auth()->user();
    $parentId = auth()->user()->id; 
    if (!$user) {
        return response()->json(['message' => 'Parent non authentifié'], 401);
    }
    $seances = Seance::where('id_parent', $parentId)->get(); 
    return response()->json($seances);
}
    public function indexEnseignant()
{
    $user=auth()->user();
    $enseignantId = auth()->user()->id; 
    if (!$user) {
        return response()->json(['message' => 'Enseignant non authentifié'], 401);
    }
    $seances = Seance::where('id_enseignant', $enseignantId)->get(); 
    return response()->json($seances);
}
public function emploiEnseignant()
{
    $user = auth()->user();
    $enseignantId = $user->id;

    $seances = Seance::with('report') // Assure-toi que la relation est définie
        ->where('id_enseignant', $enseignantId)
        ->get()
        ->map(function ($s) {
            return [
                'id_seance' => $s->id,
                'matiere' => $s->matiere,
                'jour' => $s->jour,
                'heure' => $s->heure,
                'statut' => $s->statut,
                'nouvelle_date' => $s->report->nouvelle_date ?? null,
                'motif_report' => $s->report->motif ?? null,
            ];
        });

    return response()->json($seances);
}
public function updateStatut(Request $request, $id)
{
    $statut = $request->input('statut');

    DB::table('seance_reports')
        ->where('id', $id)
        ->update([
            'statut' => $statut,
            'updated_at' => now(),
        ]);

    return response()->json(['message' => 'Statut mis à jour avec succès']);
}
public function mesReportsEnseignant(Request $request)
{
    $enseignantId = $request->user()->id;

    $rapports = DB::table('seance_reports')
        ->join('seances', 'seances.id', '=', 'seance_reports.seance_id')
        ->join('enseignants', 'enseignants.id', '=', 'seances.id_enseignant')
        ->join('eleves', 'eleves.id', '=', 'seances.id_eleve')
        ->where('seances.id_enseignant', $enseignantId)
        ->select(
            'seance_reports.id',
            'seance_reports.nouvelle_date',
            'seance_reports.motif',
            'seance_reports.statut',
            'seances.matiere',
            'seances.jour as ancien_jour',
            'seances.heure as ancienne_heure',
            DB::raw("GROUP_CONCAT(CONCAT(eleves.prenom, ' ', eleves.nom_famille) SEPARATOR ', ') as eleves")
        )
        ->groupBy(
            'seance_reports.id',
            'seance_reports.nouvelle_date',
            'seance_reports.motif',
            'seance_reports.statut',
            'seances.matiere',
            'seances.jour',
            'seances.heure'
        )
        ->orderBy('seance_reports.created_at', 'desc')
        ->get();

    return response()->json($rapports);
}
public function statistiquesValidations(Request $request)
{
    $seances = DB::table('seances')
        ->join('enseignants', 'enseignants.id', '=', 'seances.id_enseignant')
        ->leftJoin('seance_validations', 'seances.id', '=', 'seance_validations.id_seance')
        ->select(
            'seances.id',
            'seances.matiere',
            'seances.jour',
            'seances.heure',
            'enseignants.nom_famille as enseignant_nom',
            'enseignants.prenom as enseignant_prenom',
            DB::raw("SUM(CASE WHEN seance_validations.statut = 'valide' THEN 1 ELSE 0 END) as nb_valide"),
            DB::raw("SUM(CASE WHEN seance_validations.statut = 'refuse' THEN 1 ELSE 0 END) as nb_refuse"),
            DB::raw("SUM(CASE WHEN seance_validations.statut = 'en_attente' THEN 1 ELSE 0 END) as nb_en_attente")
        )
        ->groupBy('seances.id','seances.matiere','seances.jour','seances.heure','enseignants.nom_famille','enseignants.prenom')
        ->orderBy('seances.jour')
        ->get();

    return response()->json($seances);
}
public function toggleValidation($id, Request $request)
    {
        $parentId = Auth::id();

        $validation = SeanceValidation::firstOrCreate(
            ['id_seance' => $id, 'id_individu' => $parentId, 'type_individu' => 'parent'],
            ['statut' => 'planifié']
        );

        $validation->statut = $validation->statut === 'valide' ? 'planifié' : 'valide';
        $validation->save();

        return response()->json(['statut' => $validation->statut]);
    }

    // Reporter une séance
    public function reporter($id, Request $request)
    {
        $request->validate([
            'nouvelle_date' => 'required|date_format:Y-m-d\TH:i',
            'motif' => 'required|string'
        ]);

        $seance = Seance::findOrFail($id);
        $seance->jour = explode('T', $request->nouvelle_date)[0];
        $seance->heure = explode('T', $request->nouvelle_date)[1];
        $seance->save();

        // Optionnel : enregistrer le motif dans seance_validations
        $parentId = Auth::id();
        $validation = SeanceValidation::firstOrCreate(
            ['id_seance' => $id, 'id_individu' => $parentId, 'type_individu' => 'parent'],
            ['statut' => 'reporte']
        );
        $validation->statut = 'reporte';
        $validation->save();

        return response()->json(['success' => true]);
    }
    public function getSeances(Request $request)
    {
        $enseignantId = $request->user()->id;

        $seances = DB::table('seances')
            ->where('id_enseignant', $enseignantId)
            ->select('id', 'matiere', 'statut', 'jour', 'heure')
            ->get();

        return response()->json($seances);
    }
}
