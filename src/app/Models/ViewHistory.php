<?php

namespace App\Models;

use App\Models\User;
use App\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ViewHistory extends Model
{
    protected $fillable = [
        'user_id',
        'store_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function store() {
        return $this->belongsTo(Store::class);
    }
}
