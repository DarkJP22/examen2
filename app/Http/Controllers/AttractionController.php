<?php

namespace App\Http\Controllers;
use App\Models\Attraction;
use App\Models\Comment;
use App\Models\Species;

use Illuminate\Http\Request;

class AttractionController extends Controller
{
    public function index()
    {
        $attractions = Attraction::with('comments')->get()->map(function($attraction) {
            $attraction->average_rating = $attraction->averageRating();
            return $attraction;
        });

        return view('attractions.index', compact('attractions'));
    }

    public function commentsByRating(Request $request)
    {
        $minRating = $request->input('min_rating');
        $maxRating = $request->input('max_rating');

        $comments = Comment::whereBetween('rating', [$minRating, $maxRating])->get();

        return view('comments.index', compact('comments'));
    }

    public function commentCount($id)
    {
        $count = Comment::where('attraction_id', $id)->count();

        return view('comments.count', compact('count'));
    }

    public function attractionsBySpecies($speciesId)
    {
        $attractions = Attraction::whereHas('species', function($query) use ($speciesId) {
            $query->where('id', $speciesId);
        })->get();

        return view('attractions.species', compact('attractions'));
    }

    public function averageRatingBySpecies($speciesId)
    {
        $attractions = Attraction::whereHas('species', function($query) use ($speciesId) {
            $query->where('id', $speciesId);
        })->get();

        $averageRating = $attractions->pluck('averageRating')->avg();

        return view('attractions.species_average_rating', compact('averageRating'));
    }
}
