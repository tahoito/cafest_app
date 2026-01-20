<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendedItem extends Model
{
    protected $fillable = [
        'store_id',
        'name',
        'price',
        'description',
        'image',
        'sort_order',
    ];

    public function store() {
        return $this->belongsTo(Store::class);
    }
}
