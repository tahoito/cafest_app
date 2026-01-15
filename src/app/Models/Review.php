<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
use App\Models\ReviewImage;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_id',
        'rating',
        'body',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function store(){
        return $this->belongsTo(Store::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function images(){
        return $this->hasMany(ReviewImage::class);
    }
}
