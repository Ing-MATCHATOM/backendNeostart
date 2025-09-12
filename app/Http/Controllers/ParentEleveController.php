<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParentEleveRequest;
use App\Models\ParentEleve;
use Illuminate\Http\Request;

class ParentEleveController extends Controller
{
    public function store (ParentEleveRequest $request){
        $validate=$request->validated();

        ParentEleve::create($validate);
        return response()->json('cool');
    }
}
