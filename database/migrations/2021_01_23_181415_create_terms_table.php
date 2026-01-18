<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('max_lectures_per_day')->default(7);
            $table->unsignedBigInteger('academicyear_id');
            $table->foreign('academicyear_id')->references('id')->on('academic_years')->cascadeOnDelete();
            $table->unsignedBigInteger('classrooms_id');
            $table->foreign('classrooms_id')->references('id')->on('classrooms')->cascadeOnDelete();
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
        Schema::dropIfExists('terms');
    }
}
