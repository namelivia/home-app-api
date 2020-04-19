<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Status;

class AddTrashedToGarmentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
				DB::table('statuses')->insert([
					[
						'id' => Status::TRASHED,
						'name' => 'Trashed',
						'key' => 'trashed'
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
			  DB::table('statuses')->where('id', Status::TRASHED)->delete();
    }
}
