<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appendix extends Model
{
    protected $table = 'finance_appendix';
    protected $fillable = ['order_id', 'url'];
}
