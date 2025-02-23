<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VacationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class VacationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VacationType::create(['name' => 'Annual', 'total_days' => 21]);
        VacationType::create(['name' => 'Casual', 'total_days' => 7]);
    }
}
