<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_name',
        'username',
        'password',
        'email_address',
        'mobile_phone',
        'address'];
}
