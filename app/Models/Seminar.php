<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    protected $table = 'seminar';
    protected $fillable = ['course_id', 'name', 'lecturer', 'quota', 'remaining_quota',
    'start_at', 'end_at', 'closing_at', 'is_online', 'classroom', 'qrcode'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function customers()
    {
        return $this->hasMany(SeminarCustomer::class);
    }
}
