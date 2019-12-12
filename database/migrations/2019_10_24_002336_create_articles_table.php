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
            $table->string('bar_code', 128)->nullable();
            $table->string('name', 128);
            $table->decimal('cost', 8, 2);
            $table->decimal('price', 8, 2);
            $table->decimal('previus_price', 8, 2)->nullable();
            $table->decimal('offer_price', 8, 2)->nullable();
            $table->decimal('stock')->nullable();
            $table->boolean('marker')->default(0);
            $table->boolean('uncontable')->default(0);
            $table->enum('measurement', ['gramo', 'kilo'])->nullable();
            // $table->integer('amount_measurement')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('sub_user_id')->nullable()->unsigned();
            $table->integer('updated_by')->nullable()->unsigned();

            $table->foreign('user_id')
                    ->references('id')->on('users');
            $table->foreign('sub_user_id')
                    ->references('id')->on('users');
            $table->foreign('updated_by')
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
        Schema::dropIfExists('articles');
    }
}
