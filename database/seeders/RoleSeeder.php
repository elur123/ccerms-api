<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::upsert([
            ['label' => 'Admin'],
            ['label' => 'Research Coordinator'],
            ['label' => 'Subject Teacher'],
            ['label' => 'Adviser'],
            ['label' => 'Panel'],
            ['label' => 'Student'],
            ['label' => 'Statistician'],
        ], ['label']);
    }
}
