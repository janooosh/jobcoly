<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalarygroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salarygroups', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('g')->default(0); //Gutscheine
            $table->integer('a')->default(0); //AWE
            $table->integer('p')->default(0); //Wie viel MINUTEN für Gutscheine bevor man AWE kriegt?
            $table->integer('t_a'); //Zeit für AWE | in Minuten
            $table->integer('t_g'); //Zeit für Gutscheine | in Minuten
            $table->boolean('confirmed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salarygroups');
    }
}
