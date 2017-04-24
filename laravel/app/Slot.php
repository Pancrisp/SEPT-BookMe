<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    public $primaryKey  = 'slot_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['slot_id'];
}
