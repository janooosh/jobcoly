<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('shift_id');
            $table->integer('user_id');
            $table->string('status')->default('Aktiv'); //Aktiv -> wird gerade bearbeitet, Angenommen -> ist fix, Abgelehnt -> hat ned geklappt
            $table->datetime('expiration'); //Expiration of application -> accept this person automatically
            $table->mediumtext('motivation')->nullable(); //Motivation of APplicant
            $table->mediumtext('experience')->nullable(); //Experience of Applicant
            $table->mediumtext('notes')->nullable(); //For Applicant
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
