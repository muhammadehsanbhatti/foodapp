<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\EmailLogs;
use App\Models\Task;
use App\Models\Order;
Use \Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class AssignTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assigntask:cron';

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

        $installation_datetime = Carbon::now()->toDateTimeString();
        $installation_datetime = date('Y-m-d H:i:s', strtotime("-1 months", strtotime($installation_datetime)));
        $portalo_installation_datetime = date('Y-m-d H:i:s', strtotime("-7 days", strtotime($installation_datetime)));

        // email cron job
        $order_recs = Order::getOrders([
            'showroon_visit_date' => $installation_datetime,
            'paginate' => 200
        ]);
       if ($order_recs) {
            foreach ($order_recs as $order_rec) {
            
                $tasks = Task::getTask([
                    'assign_user_id' => 6,
                    'creater_user_id' => $order_rec->creater_user_id,
                    'task_description' => 'Admin assign you new task to manage showroom invite section',
                    'enquery_id' => $order_rec->enquiryDetail->id,
                    'detail' =>true
                ]);
                if (!$tasks) {
                    Task::saveUpdateTask([
                        'assign_user_id' => 6,
                        'creater_user_id' => $order_rec->creater_user_id,
                        'task_description' => 'Admin assign you new task to manage showroom invite section',
                        'enquery_id' => $order_rec->enquiryDetail->id,
                        'task_status' => 1,
                        'task_step' => 6,
                        'due_date' => now(),
                    ]);
                }
            }
       }
        

        // Assign task 
        $order_record = Order::getOrders([
            'installation_start_date' => $portalo_installation_datetime,
            // 'not_send_email_after' => 1,
            'paginate' => 200
        ]);
        if ($order_record) {
            foreach ($order_record as $order_rec) {
         
                $tasks = Task::getTask([
                    'assign_user_id' => 6,
                    'creater_user_id' => $order_rec->creater_user_id,
                    'task_description' => 'Portallo task',
                    'enquery_id' => $order_rec->enquiryDetail->id,
                    'detail' =>true
                ]);
                if ($tasks) {
                }
                else{
                    Task::saveUpdateTask([
                        'assign_user_id' => 6,
                        'creater_user_id' => $order_rec->creater_user_id,
                        'task_description' => 'Portallo task',
                        'enquery_id' => $order_rec->enquiryDetail->id,
                        'task_status' => 1,
                        'task_step' => 10,
                        'due_date' => now(),
                    ]);
                }
            }
        }
        
        \Log::info("Assign task cron job is working fine!");
        // return 0;
    }
}
