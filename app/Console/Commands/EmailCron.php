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

        $email_logs_rec = EmailLogs::getEmailLogs([
            'email_status' => 1,
            'paginate' => 200
        ]);
     
        foreach ($email_logs_rec as $email_rec) {

            $data = [
                'email' => $email_rec->email,
                'subject' => $email_rec->email_subject,
                'body' => $email_rec->email_message
            ];

            Mail::send('emails.email_template', $data, function ($message) use ($data) {
                $message->to($data['email'])
                    ->subject($data['subject']);
            });

            if (Mail::failures()) {
                // return response()->Fail('Sorry! Please try again latter');
            }else{
                // return response()->success('Great! Successfully send in your mail');
                EmailLogs::saveUpdateEmailLogs([
                    'update_id' => $email_rec->id,
                    'send_at' => Carbon::now()->toDateTimeString(),
                    'email_status' => 2,
                ]);
            }

            
        }
        $installation_datetime = Carbon::now()->toDateTimeString();
        $installation_datetime = date('Y-m-d H:i:s', strtotime("-6 months", strtotime($installation_datetime)));
        

        $order_recs = Order::getOrders([
            'installation_datetime' => $installation_datetime,
            'not_send_email_after' => 6,
            'paginate' => 200
        ]);
        // echo '<pre>';print_r($order_recs);echo '</pre>';exit;
        
        foreach ($order_recs as $order_rec) {
            $decodeShortCodesTemplate = decodeShortCodesTemplate([
                'message_id' => 13,
                'order_id' => $order_rec->id,
            ]);
            
            EmailLogs::saveUpdateEmailLogs([
                'user_id' => $order_rec->receiverUser->id,
                'email' => $order_rec->receiverUser->email,
                'email_subject' => $decodeShortCodesTemplate['email_subject'],
                'email_message' => $decodeShortCodesTemplate['email_body'],
                'email_status' => 1,
                'send_email_after' => 2,
            ]);
            
            Order::saveUpdateOrder([
                'update_id' => $order_rec->id,
                'send_email_after' => '6 Month'
            ]);
        }

        // rectification email
        $rectification_period_datetime = date('Y-m-d H:i:s', strtotime("-2 weeks", strtotime($installation_datetime)));
        $order_rectification_record = Order::getOrders([
            'rectification_period_date' => $rectification_period_datetime,
            'not_send_email_after' => 2,
        ]);
       
        foreach ($order_rectification_record as $order_rec) {
            $decodeShortCodesTemplate = decodeShortCodesTemplate([
                'message_id' => 17,
                'order_id' => $order_rec->id,
            ]);
            
            EmailLogs::saveUpdateEmailLogs([
                'user_id' => $order_rec->receiverUser->id,
                'email' => $order_rec->receiverUser->email,
                'email_subject' => $decodeShortCodesTemplate['email_subject'],
                'email_message' => $decodeShortCodesTemplate['email_body'],
                'email_status' => 1,
                'send_email_after' => 3,
            ]);
            Order::saveUpdateOrder([
                'update_id' => $order_rec->id,
                'send_email_after' => '6 Weeks'
            ]);
            
        }
    
        \Log::info("Email cron is working fine!");

    }
}