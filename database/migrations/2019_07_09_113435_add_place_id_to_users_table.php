<?php

use App\Models\Place;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlaceIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $t) {
            $t->integer('place_id')
                    ->unsigned()
                    ->default(Place::PLACE3)
                    ->after('id');
            $t->foreign('place_id')
                    ->references('id')
                    ->on('places');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropForeign('users_place_id_foreign');
            $t->dropColumn('place_id');
        });
    }
}
