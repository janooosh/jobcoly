<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftgroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shiftgroups', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name'); //Displayed on application page
            $table->string('subtitle')->nullable();
            $table->mediumtext('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shiftgroups');
    }
}
