<?php

namespace App\Models;
use App\Models\StoreHour;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Store extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone',
        'area',
        'mood',
        'budget_min',
        'budget_max',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    public function hours(){
        return $this->hasMany(StoreHour::class);
    }

}
