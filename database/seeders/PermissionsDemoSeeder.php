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
        // $role = Role::create(['name' => 'leader'])
        //     ->givePermissionTo(['access_leader','access_user', 'access_department']);
        // $role = Role::create(['name' => 'department'])
        //     ->givePermissionTo(['access_department', 'access_user']);
        $role = Role::create(['name' => 'user'])
            ->givePermissionTo(['access_user']);

        $admin = User::create([
            'first_name' => "admin",
            'last_name' => "admin",
            'email' => "admin@gmail.com",
            'password' => Hash::make('123456'),
        ]);
        $admin->assignRole('admin');

        // $leader = User::create([
        //     'first_name' => "leader",
        //     'last_name' => "leader",
        //     'email' => "leader@gmail.com",
        //     'password' => Hash::make('123456'),
        // ]);
        // $leader->assignRole('leader');

        // $department = User::create([
        //     'first_name' => "department",
        //     'last_name' => "department",
        //     'email' => "department@gmail.com",
        //     'password' => Hash::make('123456'),
        // ]);
        // $department->assignRole('department');

        $user = User::create([
            'first_name' => "Quang",
            'last_name' => "Nguyễn Văn",
            'email' => "quang@gmail.com",
            'password' => Hash::make('123456'),
        ]);
        $user->assignRole('user');
    }
}