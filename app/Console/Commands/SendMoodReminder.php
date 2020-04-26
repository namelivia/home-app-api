<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lib\Firebase\Firebase;
use GuzzleHttp\Client;
use App\Models\User;

class SendMoodReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'home:send-mood-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a reminder to track the mood';

	private $firebase;
	private $client;
	private $moodTrackerUrl;
	private $userId;

    /**
     * Create a new command instance.
     *
     * @return void
     */
	public function __construct(Firebase $firebase)
	{
		parent::__construct();
		$this->firebase = $firebase;
		$this->client = new Client(['base_uri' => $this->moodTrackerUrl]);
		$this->moodTrackerUrl = config('moodtracker.url');
		$this->userId = config('moodtracker.user_id');
	}

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
	{
		$users = [User::find($this->userId)];
		if (empty(json_decode($this->client->request('GET', 'moods/today')->getBody()))) {
			foreach ($users as $user) {
				$this->firebase->sendFirebaseNotification(
					$user, 
					'Daily mood tracker',
					'You havent input your today\'s mood yet'
				);
			}
		}
	}
}
