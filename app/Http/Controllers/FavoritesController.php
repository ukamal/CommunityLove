<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Question;

class FavoritesController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function store(Question $question){
        $question->favorites()->attach(auth()->id());
        if (request()->expectsJson()){
            return response()->json(null, 204);
        }
        return back();
    }
    public function destroy(Question $question){
        $question->favorites()->detach(auth()->id());
        if (request()->expectsJson()){
            return response()->json(null, 204);
        }
        return back();
    }
}
