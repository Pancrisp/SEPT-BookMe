<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    protected $fillable = [
        'date',
        'shift',
        'employee_id'];
}
