<?php
// app/Http/Controllers/ListeController.php
namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Temoin;
use Illuminate\Http\Request;

class ListeController extends Controller
{
    public function eleves(Request $request) {
        return Eleve::where('parent_id', $request->user()->id)
                    ->select('id', 'nom')
                    ->orderBy('nom')
                    ->get();
    }

    public function temoins(Request $request) {
        return Temoin::where('parent_id', $request->user()->id)
                     ->select('id', 'nom')
                     ->orderBy('nom')
                     ->get();
    }
}
