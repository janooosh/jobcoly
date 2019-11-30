<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPFieldToShiftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shift', function (Blueprint $table) {
            Schema::table('shifts', function(Blueprint $table) {
                $table->integer('p')->nullable(); //Nach wie vielen Stunden AWE?
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shift', function (Blueprint $table) {
            $table->dropColumn('p');
        });
    }
}
