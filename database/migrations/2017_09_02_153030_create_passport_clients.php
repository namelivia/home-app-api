<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

class CreatePassportClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('oauth_clients')->truncate();
        DB::table('oauth_clients')->insert([
            [
                'id' => 1,
                'user_id' => null,
                'name' => 'Password Grant Client',
                'secret' => 'amgFMuDwXW63hAFc82V8tXvAe5DYNmdpROH8nfNy',
                'redirect' => 'http://localhost',
                'personal_access_client' => '0',
                'password_client' => '1',
                'revoked' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
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
        DB::table('oauth_clients')->truncate();
    }
}
