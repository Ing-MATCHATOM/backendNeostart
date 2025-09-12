<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentEleve;
use App\Models\ParentEnseignant;
use App\Models\ParentTemoin;

class ParentController extends Controller
{
    public function getStats(Request $request)
    {
        $parent = $request->user();

        return response()->json([
            'enseignants' => ParentEnseignant::where('id_parent', $parent->id)->count(),
            'eleves'      => ParentEleve::where('id_parent', $parent->id)->count(),
            'temoins'     => ParentTemoin::where('id_parent', $parent->id)->count(),
            'seances'     => 0, // à adapter si tu as une table séances
        ]);
    }
}
