<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDestinationIdToLitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('litems', function (Blueprint $table) {
            $table->bigInteger('destination_id')
                    ->unsigned()
                    ->nullable()
                    ->after('name');
            $table->foreign('destination_id')
                    ->references('id')
                    ->on('destinations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('litems', function (Blueprint $table) {
            $table->dropForeign('litems_destination_id_foreign');
            $table->dropColumn('destination_id');
        });
    }
}
