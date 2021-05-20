<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeminarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seminar', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id');
            $table->string('name');
            $table->string('lecturer');
            $table->smallInteger('quota');
            $table->smallInteger('occupied_quota')->default(0);
            $table->smallInteger('group');
            $table->date('start_date_at');
            $table->time('start_time_at');
            $table->date('end_date_at');
            $table->time('end_time_at');
            $table->date('closing_date_at');
            $table->time('closing_time_at');
            $table->boolean('is_online');
            $table->string('classroom')->nullable();
            $table->string('qrcode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seminar');
    }
}
