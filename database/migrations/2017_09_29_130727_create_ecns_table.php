<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateECNsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('operator');
            $table->string('part_number');
            $table->string('sequence_number');
            $table->integer('change_request_id')->unsigned();
            $table->longtext('notes');
            $table->integer('status_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('collection_id')->unsigned();
            $table->timestamps();
        });
        
        Schema::table('ecns', function (Blueprint $table) {
            $table->foreign('collection_id')->references('id')->on('collections');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ecns');
    }
}
