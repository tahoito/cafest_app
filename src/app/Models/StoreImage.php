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

    public function getUrlAttribute()
    {
        $p = trim((string) $this->path);
        if ($p === '') return null;
        
        if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) return $p;
        
        $p = preg_replace('#^/?storage/#', '', $p);

        return Storage::url($p);
    }

}
