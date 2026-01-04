<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Review;
use App\Services\StoreRecommendService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reservation;



class StoreController extends Controller
{
    public function show($store, StoreRecommendService $service)
    {
        $storeData = $service->recommended()->firstWhere('id', (int)$store);
        abort_if(!$storeData, 404);

        $reviews = Review::with(['user','store'])
        ->where('store_id', (int)$store)
        ->latest()
        ->take(10)
        ->get();

        return view('pages.user.stores.show', [
            'store' => $storeData,
            'reviews' => $reviews, 
        ]);
    }

    public function reserveConfirm($store, Request $request, StoreRecommendService $service)
    {
        $storeData = $service->recommended()->firstWhere('id', (int)$store);
        abort_if(!$storeData, 404);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'start_time'=> ['required', 'date_format:H:i'],
            'end_time'=> ['required', 'date_format:H:i'],
            'people' => ['required', 'integer', 'min:1' , 'max:20']
        ]);

        $startAt = Carbon::parse($validated['date'].' '.$validated['start_time']);
        $endAt = Carbon::parse($validated['date'].' '.$validated['end_time']);

        session([
            'reserve.store_id' => (int)$store,
            'reserve.start_at' => $startAt->toDateTimeString(),
            'reserve.end_at' => $endAt->toDateTimeString(),
            'reserve.people' => (int)$validated['people'],
        ]);

        return view('pages.user.reserve-confirm', [
            'store' => $storeDate,
            'data' => [
                'date' => $validated['date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'people' => $validated['people'],
            ],
        ]);
    }
    public function reserveStore(Request $request, $store, StoreRecommendService $service)
    {
        $storeData = $service->recommended()->firstWhere('id', (int)$store);
        abort_if(!$storeData, 404);

        abort_unless(session('reserve.store_id') == (int)$store, 419);

        $validated = $request->validate([
            'name' => ['required','string','max:50'],
            'people' => ['required', 'string', 'max:20'],
        ]);

        $startAt = Carbon::parse(session('reserve.start_at'));
        $endAt = Carbon::parse(session('reserve.end_at'));
        $partySize = (int) session('reserve.people');

        $exits = Reservation::where('store_id', (int)$store)
            ->where('status', '!=', 'canceled')
            ->where('start_at', '<', $endAt)
            ->where('end_at', '>', $startAt)
            ->exists();

        if ($exits){
            return back()->withErrors([
                'time' => 'その時間はすでに予約が入っています。'
            ]);
        }

        Reservation::create([
            'store_id' => (int)$store,
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'start_at' => $startAt,
            'end_at' => $endAt,
            'party_size' => $partySize,
            'status' => 'confirmed',
        ]);

        session()->forget('reserve');

        return redirect()
            ->route('user.stores.show', (int)$store)
            ->with('success', '予約完了！');
    }

}

