<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_name',
        'TFN',
        'role',
        'available_days',
        'mobile_phone',
        'business_id'];
}
