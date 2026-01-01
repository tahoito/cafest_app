<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
        '映え','作業','静か','デート','ひとり','友達',
        'Wi-Fi','電源','朝カフェ','夜カフェ',
        'プリン','コーヒー','抹茶','韓国っぽ','レトロ',
        ];

        foreach ($names as $name) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($name) ?: md5($name)], // 日本語slug対策
                ['name' => $name, 'is_seed' => true]
            );
        }

    }
}
