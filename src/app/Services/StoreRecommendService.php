<?php

namespace App\Services;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class StoreRecommendService
{
    public function recommended(int $limit = 4)
    {
        $user = Auth::user();
        
        $areaIds = $user?->favorite_areas ?? [];
        $moodIds = $user?->favorite_moods ?? [];

        $base = Store::query()
            ->withAvg('reviews as rating','rating');
        
        $q1 = (clone $base);

        if(!empty($areaIds)){
            $q1->whereIn('area_id',$areaIds);
        }

        if (!empty($moodIds)) {
            $q1->whereHas('mood_id', $moodIds);
        }

        $stores = $q1->inRandomOrder()->take($limit)->get();
        if ($stores->count() >= $limit) return $stores;

        $need = $limit - $stores->count();
        $pickedIds = $stores->pluck('id')->all();

        if ($need > 0 && !empty($moodIds)) {
            $more = (clone $base)
                ->whereNotIn('id', $pickedIds)
                ->whereHas('mood_id', $moodIds)
                ->inRandomOrder()->take($need)->get();

            $stores = $stores->concat($more);
            $need = $limit - $stores->count();
            $pickedIds = $stores->pluck('id')->all();
        }
        if ($need > 0 && !empty($areaIds)) {
            $more = (clone $base)
                ->whereNotIn('id', $pickedIds)
                ->whereIn('area_id', $areaIds)
                ->inRandomOrder()->take($need)->get();

            $stores = $stores->concat($more);
            $need = $limit - $stores->count();
            $pickedIds = $stores->pluck('id')->all();
        }
        if ($need > 0) {
            $more = (clone $base)
                ->whereNotIn('id', $pickedIds)
                ->orderByDesc('rating')
                ->inRandomOrder()->take($need)->get();

            $stores = $stores->concat($more);
        }

        return $stores->values();
    }
}
