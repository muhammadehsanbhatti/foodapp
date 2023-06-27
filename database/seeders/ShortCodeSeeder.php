<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShortCode;
use DB;
use Exception;

class ShortCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

            try{
                if(DB::table('short_codes')->count() == 0){
                    DB::table('short_codes')->insert([

                        [
                            'title' => '[user_name]',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        [
                            'title' => '[login_url]',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]

                    ]);
                } else { echo "<br>[Short Code Table is not empty] "; }

            }catch(Exception $e) {
                echo $e->getMessage();
            }
            
    }
}