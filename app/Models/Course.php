<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['name', 'note', 'tags'];
    
    public function klasses()
    {
        return $this->hasMany(Klasse::class);
    }
}
