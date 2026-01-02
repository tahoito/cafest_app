<?php

namespace App\Services;

use App\Models\Tag;

class TagRecommendService
{
    public function recommended(int $limit = 5)
    {
        $user = auth('user')->user();
        $areaIds = $user?->favorite_areas ?? [];
        $moodIds = $user?->favorite_moods ?? [];

        $q = Tag::query()
            ->whereHas('reviews.store', function ($qq) use ($areaIds, $moodIds) {
                if (!empty($areaIds)) $qq->whereIn('area_id', $areaIds);
                if (!empty($moodIds)) $qq->whereIn('mood_id', $moodIds);
            })
            ->withCount('reviews')
            ->orderByDesc('reviews_count')
            ->inRandomOrder();

        $tags = $q->take($limit)->get();

        // fallback
        if ($tags->count() < $limit) {
            $need = $limit - $tags->count();

            $more = Tag::query()
                ->whereNotIn('id', $tags->pluck('id'))
                ->inRandomOrder()
                ->take($need)
                ->get();

            $tags = $tags->concat($more);
        }

        return $tags->values();
    }
}
