<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'address',
        'postcode',
        'state',
        'city',
        'active'
    ];
}
