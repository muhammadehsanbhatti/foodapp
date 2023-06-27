<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\NotificationMessage;
use DB;
use Exception;

class NotificationMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            if(DB::table('notification_messages')->count() == 0){
                DB::table('notification_messages')->insert([

                    [
                        'title' => 'Sales enquiry / Survey date confirmation',
                        'message' => 'Thank you for booking in a home survey with urbanpods, we cant wait to get started!',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Survey complete',
                        'message' => 'Thank you very much for your time and allowing us to visit your property,  it was great to meet you! The surveyor will return with your urbanpod design and cost within 48hours.
                        ',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Proposal Approval',
                        'message' => 'Thank you for your consideration. Accept the proposal to proceed.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'First invoice',
                        'message' => 'First invoice successfully sent, please change payment status to continue.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Welcome onboard',
                        'message' => 'Welcome to Onboard, the installation date and time were successfully created, please view it.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Second invoice',
                        'message' => 'Second invoice successfully sent, please change payment status to continue.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Four weeks prior to installation date',
                        'message' => 'We are nearly there for the commencement of your urbanpod installation! It is now a good time to book you in for a showroom visit to discuss the finer details of your pod.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Third invoice',
                        'message' => 'Third invoice successfully sent, please change payment status to continue.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Your installation commenced',
                        'message' => 'Your pod has started, your highly skilled urbanpod team have set off with your installation! If you have any questions, or queries relating to the installation and progress or you just want to chat to us, please call us on 01506 854844.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Installation Progress',
                        'message' => 'Your installation is well underway! we are on track to achieve the original timescale. However, things can slip or be held up, in the unlikely event this does happen we will be in touch!',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Client demo / Guarantee and Manual',
                        'message' => 'The rectification period begins at this stage, All good and enjoy your pod, any questions, just let us know!',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Fourth invoice',
                        'message' => 'Fourth invoice successfully sent, please change payment status to continue.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Aftercare',
                        'message' => 'We hope you have enjoyed your urbanpod over the last 6 months, this is just a message to say that your rectification period is about to end, please call or email the office to book in a time convenient for our team to attend to put right any faults or defects.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Thank you for survey complete',
                        'message' => 'Thank you for booking in a home survey.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Fifth invoice',
                        'message' => 'Fifth invoice successfully sent, please change payment status to continue.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Excel file uploaded',
                        'message' => 'Excel file upload from admin, kindly check.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Agreement file uploaded',
                        'message' => 'Agreement file upload from admin, kindly check.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Order confirmation uploaded',
                        'message' => 'Order confirmation upload from admin, kindly check.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],

                ]);
            } else { echo "<br>[Notification Message Table is not empty] "; }

        }catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
