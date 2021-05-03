<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeminarCustomer extends Model
{
    protected $table = 'seminar_customer';
    protected $fillable = ['seminar_id', 'customer_id', 'status'];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
