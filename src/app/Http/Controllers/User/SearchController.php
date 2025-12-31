<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StoreRecommendService;
use App\Models\User;
use App\Models\Store;

class SearchController extends Controller
{
    public function index(Request $request, StoreRecommendService $service)
    {
        $stores = $service->recommended();

        $keyword = $request->input('keyword');
        $query = Store::query();

        if ($keyword){
            $query->where('name','like',"%{$keyword}%")
                ->orWhere('area','like',"%{$keyword}%");
        }

        if ($request->filled('area')) {
            $area = (string) $request->input('area');
            $stores = $stores->filter(fn ($s) => data_get($s, 'area') === $area);
        }

        if ($request->filled('rating_min')) {
            $min = (float) $request->input('rating_min');
            $stores = $stores->filter(fn ($s) => (float) data_get($s, 'rating', 0) >= $min);
        }

        $moods = $request->input('moods', []);
        if (is_array($moods) && count($moods)){
            $stores = $stores->filter(function($s) use ($moods){
                return in_array(data_get($s, 'mood'), $moods, true);
            })-values();
        }

        $min = $request->input('rating_min');
        if ($min !== null && $min !== '') {
            $min = (float) $min;
            $stores = $stores->filter(fn($s) => (float) data_get($s, 'rating', 0) >= $min)->values();
        }


        $tags = $request->input('tags', []);
        if (is_array($tags) && count($tags)) {
            $stores = $stores->filter(function ($s) use ($tags) {
                $mood = (string) data_get($s, 'mood', '');
                return in_array($mood, $tags, true);
            });
        }


        return view('pages.user.search', compact('stores'));
    }
}
