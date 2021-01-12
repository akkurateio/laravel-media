<?php

namespace Akkurate\LaravelMedia\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsTableSeeder extends seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (config('laravel-media.permissions') as $permission) {
            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }

        foreach (config('laravel-media.roles') as $role) {
            Role::firstOrCreate([
                'name' => $role
            ]);
        }

        foreach (config('laravel-media.roles_permissions') as $key => $permissions) {
            $role = Role::where('name', '!=', 'superadmin')->where('name', $key)->first();
            foreach ($permissions as $permission) {
                $role->givePermissionTo($permission);
            }
        }
    }

}
