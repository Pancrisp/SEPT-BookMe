<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    public $primaryKey  = 'business_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['business_id'];
}
