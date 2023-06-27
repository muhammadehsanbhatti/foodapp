<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use DB;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Artisan::call('passport:install');
        // \Artisan::call('passport:client --name=chef_app --no-interaction --personal');
    }
}