<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    public $primaryKey  = 'roster_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['roster_id'];
}
