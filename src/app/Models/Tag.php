<?php

namespace App\Models;
use App\Models\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ← これ追加

class Tag extends Model
{
    use HasFactory; // ← これが Illuminate の HasFactory になる

    protected $fillable = [
        'name',
        'slug',
        'created_by_user_id',
        'is_seed',
    ];

    public function reviews()
    {
        return $this->belongsToMany(Review::class)->withTimestamps();
    }
}
