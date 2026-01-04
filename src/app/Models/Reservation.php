<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'store_id',
        'user_id',
        'name',
        'phone',
        'start_at',
        'end_at',
        'party_size',
        'status',
    ];

    public function store(){
        return $this->belongsTo(Store::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
