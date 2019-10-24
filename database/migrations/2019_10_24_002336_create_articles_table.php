<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bar_code', 128);
            $table->string('name', 128);
            $table->decimal('cost', 7, 2);
            $table->decimal('price', 7, 2);
            $table->decimal('last_price', 7, 2);
            $table->integer('stock');
            $table->integer('user_id');
            $table->integer('sub_user_id')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('articles');
    }
}
