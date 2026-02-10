<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Store;

class ReviewCsvSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('database/seeders/data/reviews.csv');
        if (!file_exists($csvPath)) {
            $this->command?->error("CSV not found: {$csvPath}");
            return;
        }

        // ① user01〜user10 を作る（既にいたら再利用）
        $userSeeds = [
            ['name' => 'Haru', 'email' => 'user01@example.com', 'icon' => '/images/users/user01.png'],
            ['name' => 'Aoi',  'email' => 'user02@example.com', 'icon' => '/images/users/user02.png'],
            ['name' => 'Yui',  'email' => 'user03@example.com', 'icon' => '/images/users/user03.png'],
            ['name' => 'Sora', 'email' => 'user04@example.com', 'icon' => '/images/users/user04.png'],
            ['name' => 'Rin',  'email' => 'user05@example.com', 'icon' => '/images/users/user05.png'],
            ['name' => 'Mao',  'email' => 'user06@example.com', 'icon' => '/images/users/user06.png'],
            ['name' => 'Aya',  'email' => 'user07@example.com', 'icon' => '/images/users/user07.png'],
            ['name' => 'Kota', 'email' => 'user08@example.com', 'icon' => '/images/users/user08.png'],
            ['name' => 'Noa',  'email' => 'user09@example.com', 'icon' => '/images/users/user09.png'],
            ['name' => 'Hina', 'email' => 'user10@example.com', 'icon' => '/images/users/user10.png'],
        ];

        $users = collect($userSeeds)->mapWithKeys(function ($u) {
            $user = User::query()->firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password123'),
                    'icon_path' => $u['icon'], // ← usersテーブル側で表示する
                ]
            );
            // 既存でもアイコン更新したいなら：
            $user->icon_path = $u['icon'];
            $user->save();

            return [$u['name'] => $user->id]; // name -> id マップ
        });

        // ② stores を email -> id で引けるようにマップ化
        $storeIdByEmail = Store::query()->pluck('id', 'email'); // stores.email がある前提

        // ③ CSV読み込みして reviews に insert
        $fh = fopen($csvPath, 'r');
        $header = fgetcsv($fh); // 1行目ヘッダー

        $rows = [];
        while (($cols = fgetcsv($fh)) !== false) {
            if (count($cols) < 6) continue;

            [$storeEmail, $userName, $userIconPath, $rating, $body, $visitedAt] = $cols;

            $storeId = $storeIdByEmail[$storeEmail] ?? null;
            $userId  = $users[$userName] ?? null;

            if (!$storeId || !$userId) {
                // 店舗emailが間違ってる / userNameが想定外
                continue;
            }

            $created = Carbon::parse($visitedAt)->setTime(12, 0, 0);

            $rows[] = [
                'store_id' => (int)$storeId,
                'user_id' => (int)$userId,
                'rating' => (float)$rating,
                'body' => $body,
                'created_at' => $created,
                'updated_at' => $created,
            ];
        }
        fclose($fh);

        // 既存のレビューを消して入れ直したいなら（デモならこれが楽）
        DB::table('reviews')->truncate();

        DB::table('reviews')->insert($rows);

        $this->command?->info('Inserted reviews: ' . count($rows));
    }
}
