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
        User::create([
            'name' => 'Admin dev',
            'email' => 'dev@gmail.com',
            'password' => Hash::make('developer'),
            'role_id' => RoleEnum::ADMIN->value,
            'status_id' => StatusEnum::APPROVED->value
        ]);
    }
}
