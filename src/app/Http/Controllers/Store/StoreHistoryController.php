<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StoreHistoryController extends Controller
{
    public function index(Request $request)
    {

        $store = auth('store')->user();
        $storeId = auth('store')->id();

        $range = $request->string('range')->toString() ?: 'week'; // all|week|month|year
        $base  = $request->string('base')->toString();            // 2025-12-14 みたいな
        $baseDate = $base ? Carbon::parse($base) : now();

        // 期間 start/end を作る
        [$start, $end, $prevStart, $prevEnd, $unit] = match ($range) {
            'month' => [
                $baseDate->copy()->startOfMonth(),
                $baseDate->copy()->endOfMonth(),
                $baseDate->copy()->subMonth()->startOfMonth(),
                $baseDate->copy()->subMonth()->endOfMonth(),
                'day',
            ],
            'year' => [
                $baseDate->copy()->startOfYear(),
                $baseDate->copy()->endOfYear(),
                $baseDate->copy()->subYear()->startOfYear(),
                $baseDate->copy()->subYear()->endOfYear(),
                'month',
            ],
            'all' => [
                Carbon::create(2000,1,1),
                now(),
                Carbon::create(2000,1,1),
                now(),
                'month',
            ],
            default /* week */ => [
                $baseDate->copy()->startOfWeek(),
                $baseDate->copy()->endOfWeek(),
                $baseDate->copy()->subWeek()->startOfWeek(),
                $baseDate->copy()->subWeek()->endOfWeek(),
                'day',
            ],
        };

        
        $rangeText = $start->format('Y/m/d') . '〜' . $end->format('Y/m/d');

        $views = DB::table('view_histories')
            ->where('store_id', $store->id)
            ->whereBetween('viewed_at', [$start, $end])
            ->count();

        $prevViews = DB::table('view_histories')
            ->where('store_id', $store->id)
            ->whereBetween('viewed_at', [$prevStart, $prevEnd])
            ->count();

        $viewsDiffPct = $prevViews > 0
            ? round((($views - $prevViews) / $prevViews) * 100)
            : ($views > 0 ? 100 : 0);

        
        $favs = DB::table('favorite_folders_store as p')
            ->join('favorite_folders as f', 'f.id', '=', 'p.favorite_folder_id')
            ->where('p.store_id', $store->id)
            ->whereBetween('p.created_at',[$start, $end])
            ->count();

        $prevFavs = DB::table('favorite_folders_store as p')
            ->join('favorite_folders as f', 'f.id', '=', 'p.favorite_folder_id')
            ->where('p.store_id', $store->id)
            ->whereBetween('p.created_at',[$prevStart, $prevEnd])
            ->count();

        $favsDiffPct = $prevFavs > 0
            ? round((($favs - $prevFavs) / $prevFavs) * 100)
            : ($favs > 0 ? 100 : 0);

        $favRate = $views > 0 ? round(($favs / $views) * 100) : null;
       
        if ($unit === 'day') {
            $rows = DB::table('view_histories')
                ->selectRaw('DATE(viewed_at) as d, COUNT(*) as c')
                ->where('store_id', $store->id)
                ->whereBetween('viewed_at', [$start, $end])
                ->groupBy('d')
                ->orderBy('d')
                ->get()
                ->keyBy('d');
            
            $labels = [];
            $values = [];
            $cursor = $start->copy();
            while ($cursor->lte($end)) {
                $key = $cursor->toDateString();
                $labels[] = $cursor->format('n/j');
                $values[] = (int) ($rows[$key]->c ?? 0);
                $cursor->addDay();
            }
        } else {
            $rows = DB::table('view_histories')
                ->selectRaw("DATE_FORMAT(viewed_at, '%Y-%m') as m, COUNT(*) as c")
                ->where('store_id', $store->id)
                ->whereBetween('viewed_at', [$start, $end])
                ->groupBy('m')
                ->orderBy('m')
                ->get()
                ->keyBy('m');

            $labels = [];
            $values = [];
            $cursor = $start->copy()->startOfMonth();
            $last = $end->copy()->startOfMonth();
            while ($cursor->lte($last)) {
                $key = $cursor->format('Y-m');
                $labels[] = $cursor->format('n');
                $values[] = (int) ($rows[$key]->c ?? 0);
                $cursor->addMonth();
            }
        }

        
        $prevBase = match ($range) {
            'month' => $baseDate->copy()->subMonth()->toDateString(),
            'year'  => $baseDate->copy()->subYear()->toDateString(),
            'all'   => $baseDate->toDateString(),
            default => $baseDate->copy()->subWeek()->toDateString(),
        };
        $nextBase = match ($range) {
            'month' => $baseDate->copy()->addMonth()->toDateString(),
            'year'  => $baseDate->copy()->addYear()->toDateString(),
            'all'   => $baseDate->toDateString(),
            default => $baseDate->copy()->addWeek()->toDateString(),
        };

        return view('pages.store.history', [
            'range' => $range,
            'rangeText' => $rangeText,
            'prevBase' => $prevBase,
            'nextBase' => $nextBase,

            'views' => $views,
            'viewsDiffPct' => $viewsDiffPct,
            'favs' => $favs,
            'favRate' => $favRate,
            'favsDiffPct' => $favsDiffPct,

            'chartLabels' => $labels,
            'chartValues' => $values,
        ]);
    }
}
