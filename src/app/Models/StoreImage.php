<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\MediaUrl;

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
        return MediaUrl::from($value);
    }
}
