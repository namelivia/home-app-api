<?php

use App\Models\Color;
use App\Models\Place;
use App\Models\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraIdsToGarmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('garments', function (Blueprint $t) {
            $t->integer('color_id')
                    ->unsigned()
                    ->default(Color::BLACK)
                    ->after('name');
            $t->foreign('color_id')
                    ->references('id')
                    ->on('colors');
        });

        Schema::table('garments', function (Blueprint $t) {
            $t->integer('place_id')
                    ->unsigned()
                    ->default(1)
                    ->after('name');
            $t->foreign('place_id')
                    ->references('id')
                    ->on('places');
        });

        Schema::table('garments', function (Blueprint $t) {
            $t->integer('status_id')
                    ->unsigned()
                    ->default(Status::OK)
                    ->after('name');
            $t->foreign('status_id')
                    ->references('id')
                    ->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('garments', function (Blueprint $t) {
            $t->dropForeign('garments_status_id_foreign');
            $t->dropColumn('status_id');
        });

        Schema::table('garments', function (Blueprint $t) {
            $t->dropForeign('garments_place_id_foreign');
            $t->dropColumn('place_id');
        });

        Schema::table('garments', function (Blueprint $t) {
            $t->dropForeign('garments_color_id_foreign');
            $t->dropColumn('color_id');
        });
    }
}
