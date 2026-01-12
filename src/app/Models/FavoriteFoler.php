<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteFoler extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'favorite_folers_store')->withTimestamps();
    }
}
