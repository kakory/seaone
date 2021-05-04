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
            //$table->smallInteger('remaining_quota');
            $table->timestamp('start_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('end_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('closing_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('is_online');
            $table->string('classroom');
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
