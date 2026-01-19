<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreImage extends Model
{
    protected $fillable = [
        'store_id','path',
        'sort_order',
        'type',
        'is_used_on_card',
        'slide_updated_at',
        'gallery_updated_at',
    ];

    protected $cast = [
        'is_used_on_card' => 'boolean',
        'slide_updated_at' => 'datetime',
        'gallery_updated_at' => 'datetime',
    ];

    public function store(){
        return $this->belongsTo(Store::class);
    }
}
