<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'course';
    protected $fillable = ['name', 'note', 'privilege_id'];
    
    public function privilege()
    {
        return $this->belongsTo(Privilege::class);
    }
}
