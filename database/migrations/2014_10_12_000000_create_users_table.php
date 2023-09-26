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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('number')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('address')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('account_number')->nullable();
            $table->integer('role')->default('0');
            $table->string('password');
            $table->integer('gel')->default('0')->nullable();
            $table->integer('usd')->default('0')->nullable();
            $table->integer('euro')->default('0')->nullable();
            $table->decimal('percent', 2, 2)->default('00.5');
            $table->string('tbc_id', 255)->nullable();
            $table->string('tbc_key', 255)->nullable();
            $table->string('payze_id', 255)->nullable();
            $table->string('payze_key', 255)->nullable();
            $table->string('payze_iban', 255)->nullable();
            $table->string('stripe_id', 255)->nullable();
            $table->string('stripe_key', 255)->nullable();
            $table->string('ipay_id', 255)->nullable();
            $table->string('ipay_key', 255)->nullable();
            $table->string('payriff_id', 255)->nullable();
            $table->string('payriff_key', 255)->nullable();
            $table->string('ipay_key', 255)->nullable();
            $table->string('sms_name', 255)->nullable();
            $table->string('sms_token', 255)->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->integer('lang_am')->default('-1');
            $table->integer('lang_az')->default('-1');
            $table->integer('lang_de')->default('-1');
            $table->integer('lang_en')->default('1');
            $table->integer('lang_ge')->default('1');
            $table->integer('lang_kz')->default('-1');
            $table->integer('lang_ru')->default('-1');
            $table->integer('lang_tj')->default('-1');
            $table->integer('lang_tr')->default('-1');
            $table->integer('lang_ua')->default('-1');
            $table->integer('lang_uz')->default('-1');

            $table->longText('ge_terms')->nullable();
            $table->longText('en_terms')->nullable();
            $table->longText('am_terms')->nullable();
            $table->longText('az_terms')->nullable();
            $table->longText('de_terms')->nullable();
            $table->longText('kz_terms')->nullable();
            $table->longText('ru_terms')->nullable();
            $table->longText('tj_terms')->nullable();
            $table->longText('tr_terms')->nullable();
            $table->longText('ua_terms')->nullable();
            $table->longText('uz_terms')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
};
