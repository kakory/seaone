<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table = 'finance_attachment';
    protected $fillable = ['order_id', 'url'];
}
