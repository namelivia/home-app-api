<?php

use App\Models\GarmentType;
use Illuminate\Database\Migrations\Migration;

class AddNewGarmentTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('garment_types')->insert([
            [
                'id' => GarmentType::TSHIRT,
                'name' => 'Tshirt',
                'key' => 'tshirt',
            ],
            [
                'id' => GarmentType::COAT,
                'name' => 'Coat',
                'key' => 'coat',
            ],
            [
                'id' => GarmentType::PIJAMA,
                'name' => 'Pijama',
                'key' => 'pijama',
            ],
            [
                'id' => GarmentType::SOCKS,
                'name' => 'Socks',
                'key' => 'socks',
            ],
            [
                'id' => GarmentType::UNDERPANTS,
                'name' => 'Underpants',
                'key' => 'underpants',
            ],
            [
                'id' => GarmentType::SWEATER,
                'name' => 'Sweater',
                'key' => 'sweater',
            ],
            [
                'id' => GarmentType::JACKET,
                'name' => 'Jacker',
                'key' => 'jacket',
            ],
            [
                'id' => GarmentType::OTHER,
                'name' => 'Other',
                'key' => 'other',
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
        DB::table('garment_types')->whereIn('id', [
            GarmentType::TSHIRT,
            GarmentType::COAT,
            GarmentType::PIJAMA,
            GarmentType::SOCKS,
            GarmentType::UNDERPANTS,
            GarmentType::SWEATER,
            GarmentType::JACKET,
            GarmentType::OTHER,
        ])->delete();
    }
}
