<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('sections')->cascadeOnDelete();
            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->integer('lucture_number'); // يبدأ من 1
            $table->integer('lucture_day'); // يبدأ من 1
            $table->time('start')->nullable();
            $table->time('end')->nullable();
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
        Schema::dropIfExists('class_tables');
    }
}
