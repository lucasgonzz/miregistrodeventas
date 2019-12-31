<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->integer('article_id')->unsigned();
            $table->integer('marker_group_id')->unsigned()->nullable();

            $table->foreign('user_id')
                    ->references('id')->on('users');
            $table->foreign('article_id')
                    ->references('id')->on('articles');
            $table->foreign('marker_group_id')
                    ->references('id')->on('marker_groups');
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
        Schema::dropIfExists('markers');
    }
}
