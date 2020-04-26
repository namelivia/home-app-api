<?php

use App\Models\Owner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnerIdToExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $t) {
            $t->integer('owner_id')
                    ->unsigned()
                    ->default(Owner::OWNER1)
                    ->after('name');
            $t->foreign('owner_id')
                    ->references('id')
                    ->on('owners');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses', function (Blueprint $t) {
            $t->dropForeign('expenses_owner_id_foreign');
            $t->dropColumn('owner_id');
        });
    }
}
