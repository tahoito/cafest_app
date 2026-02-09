<?php

namespace App\Providers;

use App\Models\Review;
use App\Models\Reservation;
use App\Observers\ReviewObserver;
use App\Observers\ReservationObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 本番は https を強制（RenderはLBでhttps終端→中身httpになりがち）
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Review::observe(ReviewObserver::class);
        Reservation::observe(ReservationObserver::class);
    }
}
