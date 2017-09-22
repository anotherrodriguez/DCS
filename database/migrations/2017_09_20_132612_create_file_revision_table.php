<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileRevisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_revision', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('revision_id')->unsigned();
            $table->integer('file_id')->unsigned();
            $table->string('path');
            $table->timestamps();
        });

        Schema::table('file_revision', function (Blueprint $table) {
            $table->foreign('revision_id')->references('id')->on('revisions');
            $table->foreign('file_id')->references('id')->on('files');
        });        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_revision');
    }
}
