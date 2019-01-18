<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            //$table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            //Own Columns
            $table->string('firstname');
            $table->string('surname');
            $table->boolean('is_student');
            $table->boolean('is_olydorf');
            $table->string('shirt_cut',1);
            $table->string('shirt_size',2);
            $table->date('birthday')->nullable();
            $table->string('mobile',20)->nullable();
            $table->string('studiengang')->nullable();
            $table->string('uni')->nullable();
            $table->decimal('semester', 2, 0)->nullable();
            $table->string('oly_cat')->nullable();
            $table->string('oly_room')->nullable();
            $table->boolean('is_verein')->default(0);
            $table->boolean('is_bierstube')->default(0);
            $table->boolean('is_disco')->default(0);
            $table->boolean('is_praside')->default(0);
            $table->boolean('is_pflichtschicht')->default(0);
            $table->string('facebook')->nullable(); //Wants T-Shirt
            $table->string('instagram')->nullable();
            $table->boolean('is_dauerjob')->default(0);
            $table->string('ausschuss')->nullable();
            $table->string('street')->nullable();
            $table->string('hausnummer')->nullable();
            $table->string('plz')->nullable();
            $table->string('ort')->nullable();
            $table->boolean('is_ehemalig')->default(0);
            $table->string('about_you')->nullable();
            $table->boolean('has_gesundheitszeugnis')->nullable();
            $table->integer('gutscheine')->default(0);
            $table->boolean('has_shirt')->default(0);
            $table->boolean('is_admin')->default(0);
            $table->boolean('is_vegetarian')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
