<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('uploader_id')->unsigned();
            $t->string('original_filename');
            $t->string('stored_filename');
            $t->integer('size')->unsigned();
            $t->string('description')->nullable();

            $t->foreign('uploader_id')
                    ->references('id')
                    ->on('users');

            $t->nullableTimestamps();
            $t->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
