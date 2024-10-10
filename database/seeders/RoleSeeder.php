<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['admin', 'user', 'viewer'];
        $permissions = [
            'create_parcel',
            'edit_parcel',
            'delete_parcel',
            'view_parcel',
            'manage_users',
            'manage_departments'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign all permissions to admin
        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->permissions()->sync(Permission::all());

        // Assign specific permissions to user and viewer
        $userRole = Role::where('name', 'user')->first();
        $userRole->permissions()->sync(Permission::whereIn('name', ['create_parcel', 'edit_parcel', 'view_parcel'])->get());

        $viewerRole = Role::where('name', 'viewer')->first();
        $viewerRole->permissions()->sync(Permission::where('name', 'view_parcel')->get());
    }
}