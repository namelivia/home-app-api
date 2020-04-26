<?php

use App\Models\Color;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colors', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name');
            $t->string('key');
        });
        DB::table('colors')->insert([
            [
                'id' => Color::WHITE,
                'name' => 'Blanco',
                'key' => 'white',
            ], [
                'id' => Color::GREY,
                'name' => 'Gris',
                'key' => 'grey',
            ], [
                'id' => Color::BLACK,
                'name' => 'Negro',
                'key' => 'black',
            ], [
                'id' => Color::MAGENTA,
                'name' => 'Magenta',
                'key' => 'magenta',
            ], [
                'id' => Color::PINK,
                'name' => 'Rosa',
                'key' => 'pink',
            ], [
                'id' => Color::RED,
                'name' => 'Rojo',
                'key' => 'red',
            ], [
                'id' => Color::BROWN,
                'name' => 'Marrón',
                'key' => 'brown',
            ], [
                'id' => Color::ORANGE,
                'name' => 'Naranja',
                'key' => 'orange',
            ], [
                'id' => Color::YELLOW,
                'name' => 'Amarillo',
                'key' => 'yellow',
            ], [
                'id' => Color::GREEN,
                'name' => 'Verde',
                'key' => 'green',
            ], [
                'id' => Color::CYAN,
                'name' => 'Cyan',
                'key' => 'cyan',
            ], [
                'id' => Color::BLUE,
                'name' => 'Azul',
                'key' => 'blue',
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
        Schema::dropIfExists('colors');
    }
}
