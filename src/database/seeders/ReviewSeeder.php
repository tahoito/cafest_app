<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Store;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/reviews.csv');

        if (!is_file($path)) {
            throw new \RuntimeException("reviews.csv not found");
        }

        $rows = array_map('str_getcsv', file($path));
        if (count($rows) < 2) return;

        $header = array_map('trim', array_shift($rows));

        foreach ($rows as $row) {
            if (count($row) !== count($header)) continue;

            $data = array_combine($header, $row);

            $user = User::where('email', trim($data['user_email'] ?? ''))->first();
            $store = Store::where('email', trim($data['store_email'] ?? ''))->first();

            if (!$user || !$store) continue;

            Review::updateOrCreate(
                [
                    'user_id'  => $user->id,
                    'store_id' => $store->id,
                    'body'     => trim($data['body'] ?? ''),
                ],
                [
                    'rating' => (float) ($data['rating'] ?? 0),
                ]
            );
        }
    }
}
