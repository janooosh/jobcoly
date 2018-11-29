<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('shift_id');
            $table->integer('user_id');
            $table->integer('application_id');
            $table->string('status')->default('Aktiv'); //Aktiv, Abgesagt
            $table->datetime('start');
            $table->datetime('end');
            $table->boolean('confirmed')->nullable();
            $table->mediumtext('notes_manager')->nullable(); //Notes from manager, visible to applicant
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignments');
    }
}
