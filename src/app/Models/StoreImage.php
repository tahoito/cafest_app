<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreImage extends Model
{
    protected $fillable = ['store_id','path','sort_order','type'];

    public function store(){
        return $this->belongsTo(Store::class);
    }
}
