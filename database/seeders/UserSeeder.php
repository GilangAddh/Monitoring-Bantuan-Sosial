<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            "name" => "admin",
            "email" => "admin@example.com",
            "password" => bcrypt("password"),
        ]);
        $admin->assignRole("admin");

        $jabar = User::create([
            "name" => "jabar",
            "email" => "jabar@example.com",
            "password" => bcrypt("password"),
        ]);
        $jabar->assignRole("daerah");

        $jateng = User::create([
            "name" => "jateng",
            "email" => "jateng@example.com",
            "password" => bcrypt("password"),
        ]);
        $jateng->assignRole("daerah");
    }
}
