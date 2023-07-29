<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;
use Exception;

class UserSeeder extends Seeder
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
                if(DB::table('users')->count() == 0){
                    DB::table('users')->insert([
                        [
                            'first_name' => 'Ahsan',
                            'last_name' => 'Super Admin',
                            'phone_number' => '12345',
                            'email' => 'superadmin@gmail.com',
                            'password' => bcrypt('12345678@w'),
                            'user_login_status' => 'super-admin',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        [
                            'first_name' => 'Ehsan',
                            'last_name' => 'Admin',
                            'phone_number' => '123456',
                            'email' => 'admin@gmail.com',
                            'password' => bcrypt('12345678@w'),
                            'user_login_status' => 'admin',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                        [
                            'first_name' => 'bhatti',
                            'last_name' => 'User',
                            'phone_number' => '1234567',
                            'email' => 'user@gmail.com',
                            'password' => bcrypt('12345678@w'),
                            'user_login_status' => 'customer',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                    ]);

                    $permissions = [
                        'user-list',
                        'user-create',
                        'user-edit',
                        'user-delete',
                        'user-status',
                        'role-list',
                        'role-create',
                        'role-edit',
                        'role-delete',
                        'permission-list',
                        'permission-create',
                        'permission-edit',
                        'permission-delete',
                        'menu-list',
                        'menu-create',
                        'menu-edit',
                        'menu-delete',
                        'sub-menu-list',
                        'sub-menu-create',
                        'sub-menu-edit',
                        'sub-menu-delete',
                        'assign-permission',
                        // 'email-template-list',
                        // 'email-template-create',
                        // 'email-template-edit',
                        // 'email-template-delete',
                        // 'shortcode-list',
                        // 'shortcode-create',
                        // 'shortcode-edit',
                        // 'shortcode-delete',
                        'edit-profile',
                        'restaurant-list',
                        'restaurant-create',
                        'restaurant-edit',
                        'restaurant-delete',
                        'restaurant_menue-list',
                        'restaurant_menue-create',
                        'restaurant_menue-edit',
                        'restaurant_menue-delete',
                    ];
                    
                    foreach ($permissions as $permission) {
                         Permission::create(['name' => $permission, 'guard_name' => 'web']);
                    }

                    $role = Role::where('name','super-admin')->first();
                    $user = User::where('id', 1)->first();
                    $permissions = Permission::pluck('id','id')->all();
                    $role->syncPermissions($permissions);
                    $user->assignRole([$role->id]);

                    $role = Role::where('name','admin')->first();
                    $user = User::where('id', 2)->first();
                    $permissions = Permission::whereIn('id',[1,2,3,4])->pluck('id','id')->all();
                    $role->syncPermissions($permissions);
                    $user->assignRole([$role->id]);

                    $role = Role::where('name','user')->first();
                    $user = User::where('id', 3)->first();
                    $permissions = Permission::whereIn('id',[31])->pluck('id','id')->all();
                    $role->syncPermissions($permissions);
                    $user->assignRole([$role->id]);

                } else { echo "<br>[User Table is not empty] "; }

            }catch(Exception $e) {
                echo $e->getMessage();
            }
            
    }
}