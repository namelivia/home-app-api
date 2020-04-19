<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCleanAndTimesWornAttributesToGarmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
			Schema::table('garments', function (Blueprint $t) {
				$t->boolean('clean')->default(1)->after('garment_type_id');
			});

			Schema::table('garments', function (Blueprint $t) {
				$t->boolean('times_worn')->default(0)->after('clean');
			});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
			Schema::table('garments', function (Blueprint $t) {
				$t->dropColumn('times_worn');
			});

			Schema::table('garments', function (Blueprint $t) {
				$t->dropColumn('clean');
			});
    }
}
