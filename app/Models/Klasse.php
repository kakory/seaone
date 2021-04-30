<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Klasse extends Model
{
    protected $fillable = ['course_id', 'name', 'lecturer', 'quota', 
    'start_time', 'end_time', 'closing_time', 'is_online', 'classroom', 'qrcode'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'enroll', 'klasse_id', 'customer_id')
        ->withPivot('status')
        ->withTimestamps();;
    }
}
