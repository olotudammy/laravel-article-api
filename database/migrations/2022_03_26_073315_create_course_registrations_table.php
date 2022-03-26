<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id")
                ->references("id")
                ->on("students")
                ->onDelete("restrict")
                ->onUpdate("restrict");
            $table->integer("semester");
            $table->string("session");
            $table->string("course_code");
            $table->string("course_title");
            $table->string("course_unit");
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
        Schema::dropIfExists('course_registrations');
    }
}
