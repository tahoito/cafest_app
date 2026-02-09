<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StoreImage extends Model
{
    protected $fillable = [
        'store_id', 'path',
        'sort_order',
        'type',
        'is_used_on_card',
    ];

    protected $casts = [
        'is_used_on_card' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function getUrlAttribute(): ?string
    {
        $value = trim((string) $this->path);
        if ($value === '') return null;

        // 外部URLはそのまま返す（storage不要）
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        // public配下の絶対パスはそのまま返す（例: /images/stores/cafe01.jpg）
        if (str_starts_with($value, '/images/')) {
            return $value;
        }

        // /storage/... はそのまま返す（すでにURLになってる想定）
        if (str_starts_with($value, '/storage/')) {
            return $value;
        }

        // "storage/xxx" や "xxx" を Storage::url に変換
        $path = preg_replace('#^/?storage/#', '', ltrim($value, '/'));
        return Storage::url($path);
    }
}
