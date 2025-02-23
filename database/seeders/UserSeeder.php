<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['name' => 'admin', 'email' => 'admin@admin.com', 'password' => bcrypt('admin')]);
        User::create(['name' => 'supervisor', 'email' => 'supervisor@supervisor.com', 'password' => bcrypt('supervisor')]);
        User::create(['name' => 'hr', 'email' => 'hr@hr.com', 'password' => bcrypt('hr')]);
        User::first()->assignRole('admin');
        User::find(2)->assignRole('supervisor');
        User::find(3)->assignRole('hr');
    }
}
