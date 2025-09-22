<?php

namespace App\Http\Controllers;

use App\Models\Emploi;
use Illuminate\Http\Request;

class EmploiController extends Controller
{
    public function recuperer() {
         $emploies=Emploi::all();
        return response()->json($emploies);
    }
}
