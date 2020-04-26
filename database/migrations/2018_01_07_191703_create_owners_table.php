<?php

use App\Models\Owner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owners', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name');
            $t->string('key');
        });
        DB::table('owners')->insert([
            [
                'id' => Owner::OWNER1,
                'name' => 'Owner1',
                'key' => 'owner1',
            ],
            [
                'id' => Owner::OWNER2,
                'name' => 'Owner2',
                'key' => 'owner2',
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
        Schema::dropIfExists('owners');
    }
}
