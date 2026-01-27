<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Tag;
use App\Services\StoreRecommendService;
use App\Models\FavoriteFolder;


class SearchController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('user')->user();

        $keyword = $request->input('keyword');
        $tags = Tag::orderBy('name')->get();

        $query = Store::query()->withAvg('reviews', 'rating');

        $isSearching =
            $request->filled('keyword') ||
            $request->filled('area') ||
            $request->filled('budget') ||
            $request->filled('time') ||
            $request->filled('moods') ||
            $request->filled('rating_min') ||
            $request->filled('tags');


        if ($request->filled('tags')) {
            $tagIds = array_values(array_unique(array_map('intval', (array)$request->input('tags'))));
            $query->whereHas('reviews.tags', function ($tq) use ($tagIds) {
                $tq->whereIn('tags.id', $tagIds);
            });
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
            $query->whereIn('mood', (array)$request->input('moods'));
        }

        if ($request->filled('rating_min')) {
            $min = (float)$request->input('rating_min');
            $query->where('reviews_avg_rating', '>=', $min);
        }

        if ($request->filled('time')) {
            $time = $request->input('time');

            if ($time === 'morning') {
                $query->whereHas('hours', function ($q) {
                    $q->where('is_closed', false)
                    ->whereNotNull('open_time')
                    ->where('open_time', '<=', '10:00:00');
                });
            } elseif ($time === 'night') {
                $query->whereHas('hours', function ($q) {
                    $q->where('is_closed', false)
                    ->whereNotNull('close_time')
                    ->where('close_time', '>=', '20:00:00');
                });
            } elseif ($time === 'now') {
                $now = now()->format('H:i:s');
                $dow = (int) now()->dayOfWeek;

                $query->whereHas('hours', function ($q) use ($dow, $now) {
                    $q->where('day_of_week', $dow)
                    ->where('is_closed', false)
                    ->where('open_time', '<=', $now)
                    ->where('close_time', '>=', $now);
                });
            }
        }

        if ($request->filled('budget')) {
            $key = $request->input('budget');
            $ranges = config('cafest.budget_ranges', []);

            if (isset($ranges[$key])) {
                [$min, $max] = $ranges[$key];

                $query->where(function ($q) use ($min, $max) {
                    $q->where('budget_max', '>=', $min);
                    if ($max !== null) {
                        $q->where('budget_min', '<=', $max);
                    }
                });
            }
        }

        $defaultFolderId = ($user)
            ? FavoriteFolder::where('user_id', $user->id)
                ->where('name', 'お気に入り')
                ->value('id')
            : null;

        $favIds = $defaultFolderId
            ? Store::whereHas('favoriteFolders', function ($q) use ($defaultFolderId) {
                $q->where('favorite_folders.id', $defaultFolderId);
            })->pluck('stores.id')->all()
            : [];

        $stores = $isSearching
            ? $query->get()
            : app(StoreRecommendService::class)->recommended(8);

        return view('pages.user.search', compact('stores', 'tags', 'isSearching','favIds'));
    }

}
