<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public $primaryKey  = 'customer_id';

    protected $fillable = [
        'customer_name',
        'username',
        'password',
        'email_address',
        'mobile_phone',
        'address'];
}
