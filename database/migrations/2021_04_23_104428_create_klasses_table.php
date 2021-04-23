<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlassesTable extends Migration
{
    public function up()
    {
        Schema::create('klasses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id');
            $table->string('lecturer');
            $table->smallInteger('total_quota');
            $table->smallInteger('remaining_quota');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->dateTime('closing_time');
            $table->boolean('is_online');
            $table->string('classroom');
            $table->string('qrcode')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('klasses');
    }
}
