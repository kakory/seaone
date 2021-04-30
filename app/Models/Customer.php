<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone_number', 'company_name', 'address', 'tags', 'photo', 'remark'];
    protected $casts = ['tags' => 'json'];

    public function classes()
    {
        return $this->belongsToMany(Klasse::Class, 'enroll', 'customer_id', 'klasse_id')
        ->withPivot('status')
        ->withTimestamps();
    }
}
