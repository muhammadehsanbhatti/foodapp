<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BusinessCuisine;
use DB;

class BusinessCuisineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            if(DB::table('business_cuisines')->count() == 0){

                DB::table('business_cuisines')->insert([

                    [
                        'cuisine_name' => 'Fast Food',
                        'cuisine_image' => 'storage/cuisine_image/1691626085_cuisine_imageone.png',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'cuisine_name' => 'Biryani Hub',
                        'cuisine_image' => 'storage/cuisine_image/1691626085_cuisine_imagetwo.jpg',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]

                ]);
                
            } else { echo "<br>[Business cuisine Table is not empty] "; }

        }catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
