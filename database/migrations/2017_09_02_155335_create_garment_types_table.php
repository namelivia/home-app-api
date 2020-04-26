<?php

use App\Models\GarmentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGarmentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garment_types', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name');
            $t->string('key');
        });
        DB::table('garment_types')->insert([
            [
                'id' => GarmentType::SHIRT,
                'name' => 'Shirt',
                'key' => 'shirt',
            ],
            [
                'id' => GarmentType::SHOE,
                'name' => 'Shoe',
                'key' => 'shoe',
            ],
            [
                'id' => GarmentType::PANTS,
                'name' => 'Pants',
                'key' => 'pants',
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
        Schema::dropIfExists('garment_types');
    }
}
