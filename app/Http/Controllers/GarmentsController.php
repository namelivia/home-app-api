<?php

namespace App\Http\Controllers;

use App\Models\Garment;
use App\Models\GarmentType;
use Illuminate\Support\Facades\Auth;

class GarmentsController extends BaseController
{
    /**
     * Corresponding model name.
     *
     * @var App\Models\Garment
     */
    protected $modelName = Garment::class;

    public function wear()
    {
        $data = $this->requestHelper->requestData();
    }

    public function getOutfit()
    {
        $placeId = Auth::user()->place_id;

        return response()->json(
            [
                'shoes' => Garment::forGarmentTypeIds(GarmentType::SHOE)
                    ->forClean()->forPlaceIds($placeId)->inRandomOrder()->first(),
                'socks' => Garment::forGarmentTypeIds(GarmentType::SOCKS)
                    ->forClean()->forPlaceIds($placeId)->inRandomOrder()->first(),
                'underwear' => Garment::forGarmentTypeIds(GarmentType::UNDERPANTS)
                    ->forClean()->forPlaceIds($placeId)->inRandomOrder()->first(),
                'shirt' => Garment::forGarmentTypeIds([GarmentType::SHIRT, GarmentType::TSHIRT])
                    ->forClean()->forPlaceIds($placeId)->inRandomOrder()->first(),
                'pants' => Garment::forGarmentTypeIds(GarmentType::PANTS)
                    ->forClean()->forPlaceIds($placeId)->inRandomOrder()->first(),
                'top' => Garment::forGarmentTypeIds([GarmentType::SWEATER, GarmentType::JACKET])
                    ->forClean()->forPlaceIds($placeId)->inRandomOrder()->first(),
                'coat' => Garment::forGarmentTypeIds([GarmentType::COAT])
                    ->forClean()->forPlaceIds($placeId)->inRandomOrder()->first(),
            ]
        );
    }
}
