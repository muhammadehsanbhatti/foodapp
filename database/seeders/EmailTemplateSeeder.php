<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;
use DB;
use Exception;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();

        // check if table email_messages is empty

            try{
                if(DB::table('email_templates')->count() == 0){
                    DB::table('email_templates')->insert([
                        [
                            'subject' => 'Account Register',
                            'message' => encrypt('Thank you for register your account'),
                            'send_on' => 'Register',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        [
                            'subject' => 'Reset Password',
                            'message' => encrypt('Your password has been reset'),
                            'send_on' => 'Reset Password',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                    ]);
                } else { echo "<br>[Email Template Table is not empty] "; }

            }catch(Exception $e) {
                echo $e->getMessage();
            }
            
    }
}