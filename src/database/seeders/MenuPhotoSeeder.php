<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuPhoto;
use App\Models\Store;

class MenuPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::pluck('id');

        foreach ($stores as $storeId) {
            MenuPhoto::insert([
                [
                    'store_id' => $storeId,
                    'photo_path' => 'images/store/menu.png',
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'store_id' => $storeId,
                    'photo_path' => 'images/store/menu.png',
                    'sort_order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'store_id' => $storeId,
                    'photo_path' => 'images/store/menu.png',
                    'sort_order' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

}
