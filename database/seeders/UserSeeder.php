<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::withoutEvents(function () {
            User::create([
                'name' => 'Admin dev',
                'email' => 'dev@umindanao.edu.ph',
                'password' => Hash::make('developer'),
                'role_id' => RoleEnum::ADMIN->value,
                'status_id' => StatusEnum::APPROVED->value
            ]);


            User::create([
                'name' => 'Charis Barbosa',
                'email' => 'cbarbosa@umindanao.edu.ph',
                'password' => Hash::make('password'),
                'role_id' => RoleEnum::RESEARCH_COORDINATOR->value,
                'status_id' => StatusEnum::APPROVED->value
            ]);
        });
    }
}
