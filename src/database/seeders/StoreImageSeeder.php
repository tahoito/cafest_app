<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\StoreImage;

class StoreImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = Store::all();

        foreach ($stores as $store){
            StoreImage::where('store_id', $store->id)->delete();

            for($i = 1; $i <= 5; $i++){
                StoreImage::create([
                    'store_id' => $store->id,
                    'path' => "/images/store/card.png",
                    'type' => 'slide',
                    'sort_order' => $i,
                ]);
            }

            for ($i = 1; $i <= 6; $i++){
                StoreImage::create([
                    'store_id' => $store->id,
                    'path' => "/images/store/image_example.png",
                    'type' => 'gallery',
                    'sort_order' => $i,
                ]);
            }
        }
    }
}
