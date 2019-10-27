<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_provider', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('article_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('article_id')
                    ->references('id')->on('articles');
            $table->foreign('user_id')
                    ->references('id')->on('users');

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
        Schema::dropIfExists('article_provider');
    }
}
