<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('part_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->integer('process_id')->unsigned();
            $table->integer('operation');
            $table->timestamps();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('part_id')->references('id')->on('parts');
            $table->foreign('type_id')->references('id')->on('types');
            $table->foreign('process_id')->references('id')->on('processes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('documents');
    }
}
