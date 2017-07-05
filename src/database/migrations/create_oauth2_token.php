<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauth2TokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('laravel-infusionsoft-oauth2.ISDK_API_TOKENTABLE'), function (Blueprint $table) {
            $table->increments('id');
            $table->text('access_token');
            $table->text('refresh_token');
            $table->integer('expires_in');
            $table->text('extra_data');
            $table->timestamps();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('laravel-infusionsoft-oauth2.ISDK_API_TOKENTABLE'));
    }
}