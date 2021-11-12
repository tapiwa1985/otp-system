<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOneTimePersonalIdentificationNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('one_time_personal_identification_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('otp');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->boolean('is_used')->default(false);
            $table->dateTime('expires_at');
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
        Schema::dropIfExists('one_time_personal_identification_numbers');
    }
}
