<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    public $primaryKey  = 'roster_id';

    protected $fillable = [
        'date',
        'shift',
        'employee_id'];
}
