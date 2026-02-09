<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Store;
use App\Models\StoreHour;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ 1ファイル運用：ここに置く
        $csvPath = base_path('database/seeders/data/stores.csv');

        if (!is_file($csvPath)) {
            // ここで止まらないと「DONEなのに入ってない」が起きるので、あえて分かるようにする
            throw new \RuntimeException("stores.csv not found: {$csvPath}");
        }

        $rows = array_map('str_getcsv', file($csvPath));
        if (!$rows || count($rows) < 2) return;

        $header = array_map('trim', array_shift($rows));

        $rows = array_map('str_getcsv', file($csvPath));
        if (!$rows || count($rows) < 2) return;

        $header = array_map('trim', array_shift($rows));
        if (isset($header[0])) {
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]); // BOM除去
        }

        $inserted = 0;
        $skipped_cols = 0;
        $skipped_email = 0;

        foreach ($rows as $row) {
            if (!$row || (count($row) === 1 && trim((string)$row[0]) === '')) continue;

            // ✅ 列数を強制的に合わせる（多い→切る、少ない→埋める）
            $row = array_slice($row, 0, count($header));
            $row = array_pad($row, count($header), null);

            // それでも combine できない時だけスキップ
            $data = @array_combine($header, $row);
            if ($data === false) {
                $skipped_cols++;
                continue;
            }

            $email = trim((string)($data['email'] ?? ''));
            if ($email === '') {
                $skipped_email++;
                continue;
            }

            // passwordが空なら固定
            $rawPassword = (string)($data['password'] ?? '');
            if (trim($rawPassword) === '') $rawPassword = 'password';

            $store = Store::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $data['name'] ?? '',
                    'email' => $email,
                    'password' => Hash::make($rawPassword),
                    'address' => $data['address'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'area' => $data['area'] ?? null,
                    'mood' => $data['mood'] ?? null,
                    'description' => $data['description'] ?? null,
                    'budget_min' => (int)($data['budget_min'] ?? 0),
                    'budget_max' => (int)($data['budget_max'] ?? 0),
                ]
            );

            $store->paymentMethods()->sync([1, 3, 4]);

            // 営業時間は今は固定でOK（opening_hours_text は表示用なら後で）
            $hours = [
                ['day_of_week' => 1, 'open_time' => '10:00', 'close_time' => '18:00', 'is_closed' => false],
                ['day_of_week' => 2, 'open_time' => null,   'close_time' => null,   'is_closed' => true ],
                ['day_of_week' => 3, 'open_time' => '10:00', 'close_time' => '18:00', 'is_closed' => false],
                ['day_of_week' => 4, 'open_time' => '10:00', 'close_time' => '18:00', 'is_closed' => false],
                ['day_of_week' => 5, 'open_time' => '10:00', 'close_time' => '18:00', 'is_closed' => false],
                ['day_of_week' => 6, 'open_time' => '09:00', 'close_time' => '19:00', 'is_closed' => false],
                ['day_of_week' => 0, 'open_time' => '09:00', 'close_time' => '19:00', 'is_closed' => false],
            ];

            foreach ($hours as $h) {
                StoreHour::updateOrCreate(
                    ['store_id' => $store->id, 'day_of_week' => $h['day_of_week']],
                    $h
                );
            }

            $inserted++;
        }
    }
}
