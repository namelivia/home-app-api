<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Place;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $t) {
					$t->increments('id');
					$t->string('name');
					$t->string('key');
        });
				DB::table('places')->insert([
					[
						'id' => Place::PLACE1,
						'name' => 'Place1',
						'key' => 'place1'
					],
					[
						'id' => Place::PLACE2,
						'name' => 'Place2',
						'key' => 'place2'
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
        Schema::dropIfExists('places');
    }
}
