<?php

namespace App\Http\Controllers;
use App\Models\ParentTemoin;
use App\Http\Requests\ParentTemoinRequest;

use Illuminate\Http\Request;

class ParentTemoinController extends Controller
{
    
    public function store (ParentTemoinRequest $request){
        $validate=$request->validated();

        ParentTemoin::create($validate);
        return response()->json('Cool');
    }
}
