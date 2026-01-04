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
        $storeModel = Store::query()
            ->where('id',(int)$store)
            ->select('stores.*')
            ->selectSub(function ($q) {
                $q->from('reviews')
                ->selectRaw('AVG(`reviews`.`rating`)')
                ->whereColumn('reviews.store_id', 'stores.id'); 
            }, 'reviews_avg_rating')
            ->where('stores.id', (int)$store)  
            ->firstOrFail();

        $reviews = Review::with(['user','store'])
            ->where('store_id', (int)$store)
            ->latest()
            ->take(10)
            ->get();

        return view('pages.user.stores.show', [
            'store' => $storeModel,
            'reviews' => $reviews,
        ]);
    }

    public function reserveConfirm(Store $store, Request $request)
    {
        $data = $request->validate([
            'date' => ['required','date'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'people' => ['required','integer','min:1'],
        ]);

        session(['reserve_data' => $data]);

        return view('pages.user.reserve-confirm', [
            'store' => $store,
            'data' => $data,
        ]);
    }


    public function reserveStore(Request $request, Store $store)
    {
        abort_unless(session('reserve.store_id') == $store->id, 419);

        $validated = $request->validate([
            'name' => ['required','string','max:50'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $startAt = Carbon::parse(session('reserve.start_at'));
        $endAt = Carbon::parse(session('reserve.end_at'));
        $partySize = (int) session('reserve.people');

        $exits = Reservation::where('store_id', $store->id)
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
            'store_id' => $store->id,
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

