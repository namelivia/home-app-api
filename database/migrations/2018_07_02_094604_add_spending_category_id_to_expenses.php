<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpendingCategoryIdToExpenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $t) {
            $t->integer('spending_category_id')
                ->unsigned()
                ->nullable()
                ->after('name');
            $t->foreign('spending_category_id')
                ->references('id')
                ->on('spending_categories');
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
            $t->dropForeign('expenses_spending_category_id_foreign');
            $t->dropColumn('spending_category_id');
        });
    }
}
