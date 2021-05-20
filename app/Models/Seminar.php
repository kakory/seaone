<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    protected $table = 'seminar';
    protected $fillable = ['course_id', 'name', 'lecturer', 'quota', 'occupied_quota', 'group', 'start_date_at', 'start_time_at', 
    'end_date_at', 'end_time_at', 'closing_date_at', 'closing_time_at', 'is_online', 'classroom', 'qrcode'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function customers()
    {
        return $this->hasMany(SeminarCustomer::class);
    }
}
