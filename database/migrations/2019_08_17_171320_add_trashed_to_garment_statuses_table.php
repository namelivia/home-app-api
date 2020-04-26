<?php

use App\Models\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
                'key' => 'trashed',
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
        DB::table('statuses')->where('id', Status::TRASHED)->delete();
    }
}
