<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;

class TopController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user','store'])
            ->latest()
            ->take(6)
            ->get();

        $stores = collect([
            [
                'id' => 1,
                'name' => 'wiik coffee',
                'area' => '栄',
                'mood' => '韓国風',
                'image_url' => null,
                'rating' => 4.2,
                'is_faved' => true,
            ],
            [
                'id' => 2,
                'name' => 'Cafe 宇',
                'area' => '大須',
                'mood' => '静か',
                'image_url' => null,
                'rating' => 3.8,
                'is_faved' => false,
            ],
            [
                'id' => 3,
                'name' => 'Morning Lab',
                'area' => '名駅',
                'mood' => '作業向き',
                'image_url' => null,
                'rating' => 4.6,
                'is_faved' => false,
            ],
            [
                'id' => 4,
                'name' => 'cafe Lob',
                'area' => '矢場町',
                'mood' => '女子会向け',
                'image_url' => null,
                'rating' => 4.5,
                'is_faved' => false,
            ],
        ]);

        return view('pages.user.top',compact('stores','reviews'));
    }

}
