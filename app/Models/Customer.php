<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $fillable = ['name', 'phone_number', 'company_name', 'adviser_id', 'photo', 'remark'];

    public function seminars()
    {
        return $this->hasMany(SeminarCustomer::class);
    }

    public function privileges()
    {
        return $this->hasMany(PrivilegeCustomer::class);
    }

    public function enroll()
    {
        return $this->belongsToMany(Seminar::Class, 'seminar_customer', 'customer_id', 'seminar_id')
        ->withPivot('status')
        ->withTimestamps();
    }

    public function contract()
    {
        return $this->belongsToMany(Privilege::Class, 'privilege_customer', 'customer_id', 'Privilege_id')
        ->withPivot('limit')
        ->withTimestamps();
    }

    public function adviser()
    {
        return $this->belongsTo(Adviser::class);
    }
}
