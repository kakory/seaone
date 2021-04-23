<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlasseCustomerTable extends Migration
{
    public function up()
    {
        Schema::create('klasse_customer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('klasse_id');
            $table->integer('customer_id');
            $table->dateTime('sign_in_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('klasse_customer');
    }
}
