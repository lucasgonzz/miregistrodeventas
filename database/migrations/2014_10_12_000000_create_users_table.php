<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->string('name', 128);
            $table->string('company_name', 128)->nullable();
            $table->string('email', 128)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 128);
            $table->integer('belongs_to')->nullable()->unsigned();
            $table->rememberToken();

            $table->foreign('belongs_to')->references('id')->on('users');
            
            $table->timestamps();
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
