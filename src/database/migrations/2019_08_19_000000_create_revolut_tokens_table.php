<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevolutTokensTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('revolut.tokens_table'), function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('type');
			$table->mediumText('value');
			$table->boolean('is_encrypted')->default(false);
			$table->timestamp('expires_at')->nullable();
			$table->timestamp('created_at')->useCurrent();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists(config('revolut.tokens_table'));
	}
}
