<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    public $primaryKey  = 'business_hour_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['business_hour_id'];
}
