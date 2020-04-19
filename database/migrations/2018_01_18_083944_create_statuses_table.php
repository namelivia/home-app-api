<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Status;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $t) {
						$t->increments('id');
						$t->string('name');
						$t->string('key');
        });

				DB::table('statuses')->insert([
					[
						'id' => Status::OK,
						'name' => 'Ok',
						'key' => 'ok'
					],
					[
						'id' => Status::DAMAGED,
						'name' => 'Dañado',
						'key' => 'damaged'
					],
					[
						'id' => Status::DESTROYED,
						'name' => 'Destruído',
						'key' => 'destroyed'
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
        Schema::dropIfExists('statuses');
    }
}
