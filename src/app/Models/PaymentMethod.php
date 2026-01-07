<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name','icon'];

    public function stores(){
        return $this->belongsToMany(Store::class, 'store_payment_methods');
    }
}
