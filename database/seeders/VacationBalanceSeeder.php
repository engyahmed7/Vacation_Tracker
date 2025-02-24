<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VacationBalance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class VacationBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // protected $fillable = [
        //     'user_id',
        //     'vacation_type_id',
        //     'total_days',
        //     'used_days',
        //     'remaining_days',
        // ];
        VacationBalance::create([
            'user_id' => 1,
            'vacation_type_id' => 1,
            'total_days' => 21,
            'used_days' => 0,
            'remaining_days' => 21,

        ]);
        VacationBalance::create([
            'user_id' => 1,
            'vacation_type_id' => 2,
            'total_days' => 7,
            'used_days' => 0,
            'remaining_days' => 7,
        ]);

        VacationBalance::create([
            'user_id' => 2,
            'vacation_type_id' => 1,
            'total_days' => 21,
            'used_days' => 0,
            'remaining_days' => 21,
        ]);

        VacationBalance::create([
            'user_id' => 2,
            'vacation_type_id' => 2,
            'total_days' => 7,
            'used_days' => 0,
            'remaining_days' => 7,
        ]);

        VacationBalance::create([
            'user_id' => 3,
            'vacation_type_id' => 1,
            'total_days' => 21,
            'used_days' => 0,
            'remaining_days' => 21,
        ]);

        VacationBalance::create([
            'user_id' => 3,
            'vacation_type_id' => 2,
            'total_days' => 7,
            'used_days' => 0,
            'remaining_days' => 7,
        ]);
    }
}
