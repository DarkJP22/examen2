<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Species;
use App\Models\Comment;
use App\Models\Attraction;

class ApiController extends Controller
{
    //

    public function listarEspecies()
    {
        $species= Species::select(['id', 'name'])->get();
        return response()->json($species);
    }


    public function obtenerEspecie($id_atraction)
    {
        $species= Attraction::select(['species_id'])->where('id', $id_atraction)->get();
        return response()->json($species);
    }
  
    
    public function update(Request $request, $id_comment)
    {
        $request->validate([
            'user_name' => 'required|string|max:50',
            'rating' => 'required|integer',
            'details' => 'required|string|max:100',
        ]);
    
        $comment = Comment::find($id_comment);

        if (!$comment) {
            return response()->json(['error' => 'El comentario no existe'], 404);
        }

        $comment->user_name = $request->user_name;
        $comment->rating = $request->rating;
        $comment->details = $request->details;
        $comment->save();

        return response()->json(['message' => 'Comentario actualizado correctamente']);
    }

    

    public function create(Request $request){
        $request->validate([
            'attraction_id' => 'required',
            'user_name' => 'required',
            'rating' => 'required',
            'details' => 'required',
        ]);

        Comment::create($request->all());

        return response()->json(['message' => 'Comentario guardado correctamente']);

    }
}
