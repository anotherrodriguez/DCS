<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevisionsFilesTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisions_files_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('revision_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->string('file_path');
            $table->timestamps();
        });

        Schema::table('revisions_files_types', function (Blueprint $table) {
            $table->foreign('revision_id')->references('id')->on('revisions');
            $table->foreign('type_id')->references('id')->on('types');
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
        Schema::dropIfExists('revisions_files_types');
    }
}
