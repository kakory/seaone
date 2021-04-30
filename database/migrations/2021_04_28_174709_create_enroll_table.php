<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollTable extends Migration
{
    public function up()
    {
        Schema::create('enroll', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('klasse_id');
            $table->integer('customer_id');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('enroll');
    }
}
