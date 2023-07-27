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
                            // 'message' => encrypt('Thank you for register your account'),
                            'message' => encrypt('<div style="font-size:16px;">
                                                    <p>Hi <b>[user_name],</b></p>
                                                    <p>We are happy that you signed up for <strong>[app_name]</strong>.</p>
                                                    <p>Welcome to <strong>[app_name]</strong>.</p>
                                                    <br>
                                                    <p>Team <strong>[app_name]</strong></p>
                                                    <br>
                                                </div>'),
                            'send_on' => 'Account Register',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        [
                            'subject' => 'Changed Password',
                            // 'message' => encrypt('Your password has been reset successfully.'),
                            'message' => encrypt('<div style="font-size:16px;">
                                                    <p>Hi <b>[user_name],</b></p>
                                                    <p>Your password has been changed successfully.</p>
                                                    <p>Your new password is [new_password].</p>
                                                    <br>
                                                    <p>Team <strong>[app_name]</strong></p>
                                                    <br>
                                                </div>'),
                            'send_on' => 'Changed Password',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        [
                            'subject' => 'Email Verification',
                            'message' => encrypt('<div style="font-size:16px;">
                                                    <p>Hi <b>[user_name],</b></p>
                                                    <p>We are very thankful for your interest in <strong>[app_name]</strong>.</p>
                                                    <p>Use this OTP to complete the verification.</p>
                                                    <p style="padding: 0px">
                                                        <br><strong>[email_verification_code]</strong>
                                                    </p>
                                                    <br>
                                                    <p>Team <strong>[app_name]</strong></p>
                                                    <br>
                                                </div>'),
                            'send_on' => 'Email Verification Through Code',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        [
                            'subject' => 'Email Verification',
                            'message' => encrypt('<div style="font-size:16px;">
                                                    <p>Hi <b>[user_name],</b></p>
                                                    <p>We are very thankful for your interest in <strong>[app_name]</strong>.</p>
                                                    <p>Please click on verify button to complete the verification process.</p>
                                                    <p style="padding: 0px;">
                                                        <br><strong>[email_verification_link]</strong>
                                                    </p>
                                                    <br>
                                                    <p>Team <strong>[app_name]</strong></p>
                                                    <br>
                                                </div>'),
                            'send_on' => 'Email Verification Through Link',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        [
                            'subject' => 'Forgot Password',
                            // 'message' => encrypt('Your password has been reset successfully.'),
                            'message' => encrypt('<div style="font-size:16px;">
                                                    <p>Hi <b>[user_name],</b></p>
                                                    <p>Your password has been reset successfully.</p>
                                                    <p>Your new password is [new_password].</p>
                                                    <br>
                                                    <p>Team <strong>[app_name]</strong></p>
                                                    <br>
                                                </div>'),
                            'send_on' => 'Forgot Password',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        [
                            'subject' => 'OTP Verification',
                            // 'message' => encrypt('Your password has been reset successfully.'),
                            'message' => encrypt('<div style="font-size:16px;">
                                                    <p>Hi <b>[user_name],</b></p>
                                                    <p>Your OTP code is [otp_code].</p>
                                                    <br>
                                                    <p>Team <strong>[app_name]</strong></p>
                                                    <br>
                                                </div>'),
                            'send_on' => 'OTP Verification',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    ]);
                } else { echo "[Email Template Table is not empty]\n"; }

            }catch(Exception $e) {
                echo $e->getMessage();
            }
            
    }
}