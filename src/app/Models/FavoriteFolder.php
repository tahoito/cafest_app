<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteFolder extends Model
{
    protected $fillable = ['user_id','name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stores()
    {
        return $this->belongsToMany(
            Store::class, 
            'favorite_folders_store')->withTimestamps();
    }

    public function latestStore() {
        return $this->stores()->latest('favorite_folders_store.created_at');
    }

}
