<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Place;

class AddPlace3ToPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
				DB::table('places')->insert([
					[
						'id' => Place::PLACE3,
						'name' => 'Place3',
						'key' => 'place3'
					]
				]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
			  DB::table('places')->where('id', Place::PLACE3)->delete();
    }
}
