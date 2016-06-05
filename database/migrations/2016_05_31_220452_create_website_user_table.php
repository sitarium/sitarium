<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsiteUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::transaction(function()
		{
			Schema::create('website_user', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('website_id')->unsigned();
				$table->integer('user_id')->unsigned();
			});
	
			Schema::table('website_user', function(Blueprint $table)
			{
				$table->foreign('website_id')->references('id')->on('websites');
				$table->foreign('user_id')->references('id')->on('users');
				//
				$table->unique(array('website_id', 'user_id'));
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
		DB::transaction(function()
		{
			Schema::drop('website_user');
		});
	}

}
