<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone', 
        'label', 
        'address_line', 
        'city', 
        'province', 
        'postal_code',
    ];
}
