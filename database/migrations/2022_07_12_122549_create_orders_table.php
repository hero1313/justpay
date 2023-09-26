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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('front_code');
            $table->integer('valuta');
            $table->string('full_name')->nullable()->default(0);
            $table->string('number')->nullable()->default(0);
            $table->string('address')->nullable()->default(0);
            $table->string('email')->nullable()->default(0);
            $table->string('id_number')->nullable()->default(0);
            $table->string('special_code')->nullable()->default(0);
            $table->string('tbc')->nullable()->default(0);
            $table->string('ipay')->nullable()->default(0);
            $table->string('payze')->nullable()->default(0);
            $table->string('payze_split')->nullable()->default(0);
            $table->string('payriff')->nullable()->default(0);
            $table->string('stripe')->nullable()->default(0);
            $table->string('description')->nullable();
            $table->string('qr')->nullable();
            $table->unsignedDecimal('total', 8, 2)->default(0);
            $table->unsignedDecimal('shiping', 8, 2)->default(0);
            $table->string('status')->default(0);
            $table->string('ge_name')->nullable();
            $table->string('en_name')->nullable();
            $table->string('am_name')->nullable();
            $table->string('az_name')->nullable();
            $table->string('de_name')->nullable();
            $table->string('kz_name')->nullable();
            $table->string('ru_name')->nullable();
            $table->string('tj_name')->nullable();
            $table->string('tr_name')->nullable();
            $table->string('ua_name')->nullable();
            $table->string('uz_name')->nullable();

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
};
