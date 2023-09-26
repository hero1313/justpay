<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('order_id');
            $table->string('name')->nullable();
            $table->decimal('price')->nullable();
            $table->string('amount')->default('1');
            $table->integer('currency')->default('1');
            $table->string('description')->nullable();
            $table->string('code')->nullable();
            $table->string('image')->default('product.png')->nullable();
            $table->string('save_index')->default('0');
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
        Schema::dropIfExists('products');
    }
};
