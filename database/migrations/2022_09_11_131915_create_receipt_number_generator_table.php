<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReceiptNumberGeneratorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('receipt_number_generator', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('branch_id');
			$table->integer('running_number');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('receipt_number_generator');
	}

}
