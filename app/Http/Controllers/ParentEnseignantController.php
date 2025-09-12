<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParentEnseignantRequest;
use App\Models\ParentEnseignant;
use Illuminate\Http\Request;

class ParentEnseignantController extends Controller
{
    public function store(ParentEnseignantRequest $request){
        $validate=$request->validated();

        ParentEnseignant::create($validate);
        return response()->json('cool');
    }
}
