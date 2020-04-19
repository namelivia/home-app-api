<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\GarmentType;

class AddGarmentTypeIdToGarmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
			Schema::table('garments', function (Blueprint $t) {
				$t->integer('garment_type_id')
					->unsigned()
					->default(GarmentType::SHIRT)
					->after('name');
				$t->foreign('garment_type_id')
					->references('id')
					->on('garment_types');
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
				$t->dropForeign('garments_garment_type_id_foreign');
				$t->dropColumn('garment_type_id');
			});
    }
}
