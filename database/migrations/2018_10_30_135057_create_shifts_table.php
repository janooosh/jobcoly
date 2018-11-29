<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('job_id')->default(0);
            $table->integer('shiftgroup_id')->default(0);
            $table->datetime('starts_at');
            $table->datetime('ends_at');
            $table->string('area')->nullable();
            $table->integer('anzahl')->default(0);
            $table->boolean('active')->default(0);
            $table->string('status')->default('Aktiv');
            $table->mediumText('description')->nullable(); //Für Manager, kann ggf. angezeigt werden
            $table->integer('gutscheine')->default(0); //# Gutscheine / Stunde
            $table->integer('awe')->default(0); //AWE pro Stunde
            $table->boolean('confirmed')->nullable();
            $table->boolean('pflicht')->default(1); // Kann als Pflichtschicht angerechnet werden? Default: true
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shifts');
    }
}
