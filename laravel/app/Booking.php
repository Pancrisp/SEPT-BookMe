<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
