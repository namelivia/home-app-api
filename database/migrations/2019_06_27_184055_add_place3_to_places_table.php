<?php

use App\Models\Place;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
                'id' => 3,
                'name' => 'Place3',
                'key' => 'place3',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('places')->where('id', 3)->delete();
    }
}
