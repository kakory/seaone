<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivilegeCustomer extends Model
{
    protected $table = 'privilege_customer';
    protected $fillable = ['privilege_id', 'customer_id', 'limit'];

    public function privilege()
    {
        return $this->belongsTo(Privilege::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
