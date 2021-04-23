<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['name', 'note', 'is_VIP', 'is_incu', 'is_bench'];

    public function klasses()
    {
        return $this->hasMany(Klasse::class);
    }
}
