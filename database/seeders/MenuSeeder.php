<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;
use Exception;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();

        // check if table users is empty

            try{
                if(DB::table('menus')->count() == 0){
                    DB::table('menus')->insert([
                        [
                            'title' => 'Roles',
                            'url' => '/role',
                            'slug' => 'role-list',
                            'sort_order' => 1,
                            'status' => 'Published',
                            'asset_type' => 'Icon',
                            'asset_value' => 'award',
                            'created_at' => now(),	
                            'updated_at' => now(),
                        ],
                        [
                            'title' => 'Permissions',
                            'url' => '/permission',
                            'slug' => 'permission-list',
                            'sort_order' => 2,
                            'status' => 'Published',
                            'asset_type' => 'Icon',
                            'asset_value' => 'lock',
                            'created_at' => now(),	
                            'updated_at' => now(),
                        ],
                        [
                            'title' => 'Assign Permissions',
                            'url' => '/assign_permission',
                            'slug' => 'assign-permission',
                            'sort_order' => 3,
                            'status' => 'Published',
                            'asset_type' => 'Icon',
                            'asset_value' => 'unlock',
                            'created_at' => now(),	
                            'updated_at' => now(),
                        ],
                        [
                            'title' => 'Users',
                            'url' => '/user',
                            'slug' => 'user-list',
                            'sort_order' => 4,
                            'status' => 'Published',
                            'asset_type' => 'Icon',
                            'asset_value' => 'user',
                            'created_at' => now(),	
                            'updated_at' => now(),
                        ],
                        [
                            'title' => 'Menus',
                            'url' => '/menu',
                            'slug' => 'menu-list',
                            'sort_order' => 5,
                            'status' => 'Published',
                            'asset_type' => 'Icon',
                            'asset_value' => 'menu',
                            'created_at' => now(),	
                            'updated_at' => now(),
                        ],
                        [
                            'title' => 'Sub Menus',
                            'url' => '/sub_menu',
                            'slug' => 'sub-menu-list',
                            'sort_order' => 6,
                            'status' => 'Published',
                            'asset_type' => 'Icon',
                            'asset_value' => 'menu',
                            'created_at' => now(),	
                            'updated_at' => now(),
                        ],
                        // [
                        //     'title' => 'Email Templates',
                        //     'url' => '/email_template',
                        //     'slug' => 'email-template-list',
                        //     'sort_order' => 7,
                        //     'status' => 'Published',
                        //     'asset_type' => 'Icon',
                        //     'asset_value' => 'mail',
                        //     'created_at' => now(),	
                        //     'updated_at' => now(),
                        // ],
                        // [
                        //     'title' => 'Short Codes',
                        //     'url' => '/short_codes',
                        //     'slug' => 'shortcode-list',
                        //     'sort_order' => 8,
                        //     'status' => 'Published',
                        //     'asset_type' => 'Icon',
                        //     'asset_value' => 'code',
                        //     'created_at' => now(),	
                        //     'updated_at' => now(),
                        // ],
                        [
                            'title' => 'General Title',
                            'url' => '/general_title',
                            'slug' => 'general-title-list',
                            'sort_order' => 7,
                            'status' => 'Published',
                            'asset_type' => 'Icon',
                            'asset_value' => 'menu',
                            'created_at' => now(),	
                            'updated_at' => now(),
                        ],
                        [
                            'title' => 'Goal',
                            'url' => '/goal',
                            'slug' => 'goal-list',
                            'sort_order' => 8,
                            'status' => 'Published',
                            'asset_type' => 'Icon',
                            'asset_value' => 'menu',
                            'created_at' => now(),	
                            'updated_at' => now(),
                        ],
                        [
                            'title' => 'Industry Vertical Item',
                            'url' => '/industry_vertical_item',
                            'slug' => 'industry-vertical-item-list',
                            'sort_order' => 9,
                            'status' => 'Published',
                            'asset_type' => 'Icon',
                            'asset_value' => 'menu',
                            'created_at' => now(),	
                            'updated_at' => now(),
                        ],
                        [
                            'title' => 'Professional Role Type',
                            'url' => '/professional_role_type',
                            'slug' => 'professional-role-type-list',
                            'sort_order' => 10,
                            'status' => 'Published',
                            'asset_type' => 'Icon',
                            'asset_value' => 'menu',
                            'created_at' => now(),	
                            'updated_at' => now(),
                        ],
                    ]);

                } else { echo "<br>[Menus Table is not empty] "; }

            }catch(Exception $e) {
                echo $e->getMessage();
            }
            
    }
}