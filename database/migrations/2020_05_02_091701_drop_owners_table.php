<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('owners');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('owners', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name');
            $t->string('key');
        });
        DB::table('owners')->insert([
            [
                'id' => 1,
                'name' => 'Owner1',
                'key' => 'owner1',
            ],
            [
                'id' => 2,
                'name' => 'Owner2',
                'key' => 'owner2',
            ],
        ]);
    }
}
