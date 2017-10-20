<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartMatlDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('parts', function (Blueprint $table) {
            $table->string('description')->after('part_number');
            $table->string('material')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('parts', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('material');
        });
    }
}
