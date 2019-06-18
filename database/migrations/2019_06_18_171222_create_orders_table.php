<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('menu_name');
            $table->integer('quantity');
            $table->boolean('paid')->default(0);
            $table->string('flavor_choice')->nullable();
            $table->integer('menu_price');
            $table->string('note')->nullable();
            $table->date('menu_date');
            $table->integer('user_rice');
            $table->integer('user_vegetable');
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
        Schema::dropIfExists('orders');
    }
}
