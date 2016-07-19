<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserWebsiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::create('user_website', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->integer('website_id')->unsigned();
            });

            Schema::table('user_website', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('website_id')->references('id')->on('websites');
                //
                $table->unique(['user_id', 'website_id']);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::transaction(function () {
            Schema::drop('user_website');
        });
    }
}
