<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('variants', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('slug');
			$table->string('code')->nullable();
			$table->string('ean')->nullable();
			$table->integer('weight')->default('0');
			$table->integer('stock')->default('0');
			$table->integer('parent_id');
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
		Schema::drop('variants');
	}

}
