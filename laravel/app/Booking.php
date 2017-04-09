<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public $primaryKey  = 'booking_id';

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'customer_id',
        'business_id'];
}
