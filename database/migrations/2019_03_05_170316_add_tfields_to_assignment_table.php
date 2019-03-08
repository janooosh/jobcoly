<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTfieldsToAssignmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->integer('t_a')->default(0); //Zeit für AWE
            $table->integer('t_g')->default(0); //Zeit für Gutscheine
            $table->boolean('accepted')->default(0); //Vom User akzeptiert
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assignments', function (Blueprint $table) {
            //
        });
    }
}
