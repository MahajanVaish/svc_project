<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           $permissions = [
            'view branches',
            'create branches',
            'edit branches',
            'delete branches',
            'view admin pages',
            'access all branches',
        ];


         foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

           $superAdmin = Role::create(['name' => 'superadmin']);
        $superAdmin->givePermissionTo(Permission::all());



         $svc = Role::create(['name' => 'SVC']);
        $svc->givePermissionTo(['view branches']);
    }
}
