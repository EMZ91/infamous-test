<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function($table) {
            $table->increments('id');
            $table->string('email', 100)->unique();
            $table->string('name', 50);
            $table->string('phone', 20);
            $table->tinyInteger('gender');
            $table->date('date_of_birth');
            $table->text('biography');
            $table->string('profile_picture', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('users');
    }

}
