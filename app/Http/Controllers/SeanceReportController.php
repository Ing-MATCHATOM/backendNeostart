<?php

namespace App\Http\Controllers;

use App\Models\Seance;
use App\Models\SeanceReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeanceReportController extends Controller
{
    // Enregistrer un report
    public function reporter(Request $request, $id)
    {
        $request->validate([
            'nouvelle_date' => 'required|date',
            'motif' => 'required|string',
        ]);

        $seance = Seance::findOrFail($id);

        // Crée un report
        SeanceReport::create([
            'seance_id' => $seance->id,
            'nouvelle_date' => $request->nouvelle_date,
            'motif' => $request->motif,
        ]);

        // Met à jour le statut de la séance
        $seance->statut = 'reporte';
        $seance->save();

        return response()->json(['message' => 'Séance reportée avec succès']);
    }

    // Afficher tous les reports d’un enseignant
    public function indexByEnseignant()
    {
        $user = auth()->user();
        $enseignantId = $user->id;

        $reports = SeanceReport::with(['seance'])
            ->whereHas('seance', function ($query) use ($enseignantId) {
                $query->where('id_enseignant', $enseignantId);
            })
            ->get();

        return response()->json($reports);
    }

public function mesReports(Request $request)
{
    $parentId = $request->user()->id;

    $rapports = DB::table('seance_reports')
        ->join('seances', 'seances.id', '=', 'seance_reports.seance_id')
        ->join('enseignants', 'enseignants.id', '=', 'seances.id_enseignant')
        ->join('eleves', 'eleves.id', '=', 'seances.id_eleve')
        ->where('seances.id_parent', $parentId)
        ->select(
            'seance_reports.id',
            'seance_reports.nouvelle_date',
            'seance_reports.motif',
            'seance_reports.statut',
            'seances.matiere',
            // ✅ Anciennes infos de la séance
            'seances.jour as ancien_jour',
            'seances.heure as ancienne_heure',
            'enseignants.nom_famille as enseignant_nom',
            'enseignants.prenom as enseignant_prenom',
            DB::raw("GROUP_CONCAT(CONCAT(eleves.prenom, ' ', eleves.nom_famille) SEPARATOR ', ') as eleves")
        )
        ->groupBy(
            'seance_reports.id',
            'seance_reports.nouvelle_date',
            'seance_reports.motif',
            'seance_reports.statut',
            'seances.matiere',
            'seances.jour',
            'seances.heure',
            'enseignants.nom_famille',
            'enseignants.prenom'
        )
        ->orderBy('seance_reports.created_at', 'desc')
        ->get();

    return response()->json($rapports);
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
}

