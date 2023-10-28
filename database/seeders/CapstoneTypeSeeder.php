<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\CapstoneType;
class CapstoneTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CapstoneType::upsert([
            ['label' => 'Capstone One'],
            ['label' => 'Capstone Two'],
        ], ['label']);
    }
}
