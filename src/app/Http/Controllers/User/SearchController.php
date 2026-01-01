<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Tag;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $tagId = $request->query('tag');
        $keyword = $request->input('keyword');

        $tag = $tagId ? Tag::find($tagId) : null;

        $query = Store::query()
            ->withAvg('reviews as rating', 'rating');

       
        if ($tagId) {
            $query->whereHas('reviews.tags', fn($q) => $q->where('tags.id', $tagId));
        }

        
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('area', 'like', "%{$keyword}%");
            });
        }

       
        if ($request->filled('area')) {
            $query->where('area', $request->input('area'));
        }

      
        if ($request->filled('moods')) {
            $query->whereIn('mood', (array) $request->input('moods'));
        }

        
        if ($request->filled('rating_min')) {
            $min = (float) $request->input('rating_min');
            $query->having('rating', '>=', $min);
        }

        $stores = $query->get();

        return view('pages.user.search', compact('stores', 'tag'));
    }
}
