<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\FcmToken;
Use \Carbon\Carbon;

class TokensDeletedCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // $new_time = date("Y-m-d H:i:s", strtotime('-30 minutes'));
        $last_seen = Carbon::now()->toDateTimeString();
        $last_seen = date('Y-m-d H:i:s', strtotime("-30 minutes", strtotime($last_seen)));

        $getusers = User::getUser([
            'login_having_thirty_minutes' => $last_seen,
            'comma_separated_ids' => true,
            // 'paginate' => 200
        ]);
        if(isset($getusers->ids)){
            FcmToken::deleteFCM_Token(0, [
                'user_id_in' => explode(',',$getusers->ids),
            ]);
        }
        \Log::info("Token deleted cron is working fine!");
        // return true;
    }
}
