<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration 
{
	public function up()
	{
		Schema::create('courses', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('note');
            $table->boolean('is_VIP');
            $table-> boolean('is_incu');
            $table-> boolean('is_bench');
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::dropIfExists('courses');
	}
}
