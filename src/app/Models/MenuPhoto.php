<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPhoto extends Model
{
    protected $fillable = [
        'store_id',
        'photo_path',
        'sort_order',
    ];

    public function store() {
        
        return $this->belongsTo(Store::class);  
    }
}

