<?php

use App\Models\Color;
use App\Models\Destination;
use App\Models\Garment;
use App\Models\GarmentType;
use App\Models\Place;
use App\Models\SpendingCategory;
use App\Models\Status;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;
    static $firebaseToken;

    return [
        'username' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
    ];
});

$factory->define(Garment::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'garment_type_id' => $faker->numberBetween(GarmentType::SHIRT, GarmentType::OTHER),
        'color_id' => $faker->numberBetween(Color::WHITE, Color::BLUE),
        'status_id' => $faker->numberBetween(Status::OK, Status::TRASHED),
        'place_id' => function () {
            return factory(Place::class)->create()->id;
        },
    ];
});

$factory->define(Place::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(SpendingCategory::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(Destination::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});
