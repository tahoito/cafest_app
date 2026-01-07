<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model
{
    protected $fillable = ['review_id','path','sort'];

    public function review()
    {
        return $this->belongsTo(\App\Models\Review::class);
    }

}
