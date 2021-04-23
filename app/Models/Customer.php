<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone_number', 'company_name', 'address', 'is_VIP', 'is_incu', 'is_bench', 'photo'];

    public function classes()
    {
        return $this->belongsToMany(Klasse::Class, 'klasse_customer', 'customer_id', 'klasse_id');
    }
}
