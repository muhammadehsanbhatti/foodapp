<?php

namespace App\Console\Commands;

// use App\Http\Controllers\Controller;

use App\Models\EmailLogs;
use App\Models\Order;
Use \Carbon\Carbon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmailCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:cron';

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
        $EmailLogObj = new EmailLogs();
        $email_logs_rec = $EmailLogObj->getEmailLogs([
            'email_status' => 'Pending'
        ]);
     
        foreach ($email_logs_rec as $email_rec) {

            $data = [
                'email' => $email_rec->email,
                'subject' => $email_rec->email_subject,
                'body' => $email_rec->email_message
            ];

            \Log::info("Great! Email cron job is working fine!");
            if(Mail::send('emails.email_template', $data, function ($message) use ($data) {
                $message->to($data['email'])
                    ->subject($data['subject']);
            })){
                \Log::info("Success! Email send successfully to ".$data['email']);
                // return response()->success('Great! Successfully send in your mail');
                $EmailLogObj->saveUpdateEmailLogs([
                    'update_id' => $email_rec->id,
                    'send_at' => Carbon::now()->toDateTimeString(),
                    'email_status' => 'Send',
                ]);

            }else{
                \Log::info("Sorry! Email not sended to ".$data['email']);
                // return response()->Fail('Sorry! Please try again latter');
                $EmailLogObj->saveUpdateEmailLogs([
                    'update_id' => $email_rec->id,
                    'stop_at' => Carbon::now()->toDateTimeString(),
                    'email_status' => 'Stop',
                ]);
            }

            // \Log::info("Great! Email cron job is working fine!");

            // if( count(Mail::failures()) > 0 ) {
            //     foreach(Mail::failures() as $email_address) {
            //         \Log::info("Sorry! Email not sended to ".$email_address);
            //         // return response()->Fail('Sorry! Please try again latter');
            //         $EmailLogObj->saveUpdateEmailLogs([
            //             'update_id' => $email_rec->id,
            //             'stop_at' => Carbon::now()->toDateTimeString(),
            //             'email_status' => 'Stop',
            //         ]);
            //     }
            // }else{
            //     \Log::info("Success! Email send successfully to ".$data['email']);
            //     // return response()->success('Great! Successfully send in your mail');
            //     $EmailLogObj->saveUpdateEmailLogs([
            //         'update_id' => $email_rec->id,
            //         'send_at' => Carbon::now()->toDateTimeString(),
            //         'email_status' => 'Send',
            //     ]);
            // }
        }

    }
}