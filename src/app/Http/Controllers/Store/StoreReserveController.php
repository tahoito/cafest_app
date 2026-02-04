<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Notifications\StoreReservationVisitedNotification;




class StoreReserveController extends Controller
{
    public function index() {
        $reservations = Reservation::where('store_id',auth('store')->id())
            ->whereNull('visited_at')
            ->orderBy('start_at')
            ->get();

        return view('pages.store.reserve', compact('reservations'));
    }


    public function visit(Reservation $reservation, Request $request)
    {
        abort_unless($reservation->store_id === auth('store')->id(), 403);

        $reservation->visited_at = now();
        $reservation->save();

        $store = auth('store')->user();
        $store->notify(new StoreReservationVisitedNotification($reservation));

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', '来店済みにしました');
    }

}
