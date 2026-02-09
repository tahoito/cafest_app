<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\StoreImage;

class StoreImageSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/store_images.csv');
        if (!file_exists($path)) return;

        $rows = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($rows));

        $storeMap = Store::pluck('id', 'email');

        foreach ($rows as $row) {
            if (count($row) !== count($header)) continue;
            $data = array_combine($header, $row);

            $email = trim($data['store_email'] ?? '');
            if (!$email || !$storeMap->has($email)) continue;

            $storeId = $storeMap[$email];

            StoreImage::updateOrCreate(
                [
                    'store_id' => $storeId,
                    'sort_order' => (int)($data['sort_order'] ?? 1),
                    'type' => $data['type'] ?? null,
                ],
                [
                    'store_id' => $storeId,
                    'path' => trim($data['path'] ?? ''),
                    'sort_order' => (int)($data['sort_order'] ?? 1),
                    'type' => $data['type'] ?? null,
                    'is_used_on_card' => (bool) ((int)($data['is_used_on_card'] ?? 0)),
                ]
            );
        }
    }
}
