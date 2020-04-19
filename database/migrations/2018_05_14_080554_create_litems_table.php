<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLitemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('litems', function (Blueprint $t) {
			$t->increments('id');
			$t->string('name');
			$t->text('description');
			$t->timestamps();
			$t->integer('parent_id')->unsigned()->nullable();
			$t->foreign('parent_id')
				->references('id')
				->on('litems');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('litems');
	}
}
