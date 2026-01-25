<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StoreImage extends Model
{
    protected $fillable = [
        'store_id','path',
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
        
        if (!$value) return null;
        if (str_starts_with($value,'http://') || str_starts_with($value, 'http://')) {
            return $value;
        } 

        if (str_starts_with($value, '/storage/')) {
            return $value;
        }

        $path = preg_replace('#^/?storage/#', '', ltrim($value, '/' ));
        return Storage::url($path);
    }

}
