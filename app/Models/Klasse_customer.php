<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Klasse_customer extends Model
{
    protected $fillable = ['klasse_id', 'customer_id', 'sign_in_time'];
}
