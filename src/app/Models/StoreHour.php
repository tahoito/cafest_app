<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'day_of_week',
        'open_time',
        'close_time',
        'is_closed',
    ];

    protected $cast = [
        'is_closed' => 'boolean',
        'open_time' => 'datetime:H:i',
        'closed_time' => 'date:H:i',
    ];

    public function store(){
        return $this->booleansTo(Store::class);
    }
}
