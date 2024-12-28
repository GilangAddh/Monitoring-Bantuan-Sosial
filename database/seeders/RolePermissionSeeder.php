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
        Permission::create(["name" => "daftar-laporan"]);
        Permission::create(["name" => "verifikasi-laporan"]);

        Role::create(["name" => "admin"]);
        Role::create(["name" => "daerah"]);

        $roleAdmin = Role::findByName("admin");
        $roleAdmin->givePermissionTo("verifikasi-laporan");

        $roleDaerah = Role::findByName("daerah");
        $roleDaerah->givePermissionTo("daftar-laporan");
    }
}
