<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PermissionsDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'access_admin']);
        // Permission::create(['name' => 'access_leader']);
        // Permission::create(['name' => 'access_department']);
        Permission::create(['name' => 'access_user']);
        $role = Role::create(['name' => 'admin'])
            ->givePermissionTo(['access_admin']);

        $role = Role::create(['name' => 'user'])
            ->givePermissionTo(['access_user']);

        $admin = User::create([
            'first_name' => "admin",
            'last_name' => "admin",
            'user_name' => "admin",
            'avatar' => '/images/avatar.gif',
            'email' => "admin@gmail.com",
            'password' => Hash::make('123456'),
        ]);
        $admin->assignRole('admin');

        $user = User::create([
            'first_name' => "Quang",
            'last_name' => "Nguyễn Văn",
            'user_name' => "QuangFoden",
            'avatar' => '/images/avatar.gif',
            'email' => "quang@gmail.com",
            'password' => Hash::make('123456'),
        ]);
        $user->assignRole('user');
    }
}
