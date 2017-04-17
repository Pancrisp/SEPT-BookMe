<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $primaryKey  = 'employee_id';

    protected $fillable = [
        'employee_name',
        'TFN',
        'activity_id',
        'available_days',
        'mobile_phone',
        'business_id'];
}
