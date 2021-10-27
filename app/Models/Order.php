<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'finance_order';
    protected $fillable = ['admin_user_id', 'type', 'date', 'customer_id', 'service_id', 
        'amount_of_money', 'type_of_payment', 'voucher', 'note', 'step'];

    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function appendixes()
    {
        return $this->hasMany(Appendix::class);
    }
}
