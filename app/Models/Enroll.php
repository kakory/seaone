<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enroll extends Model
{
    protected $table = 'enroll';
    protected $fillable = ['klasse_id', 'customer_id', 'status'];
}
