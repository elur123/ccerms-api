<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\SectionType;
class SectionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SectionType::upsert([
            ['label' => '1st Semester'],
            ['label' => '2nd Semester'],
            ['label' => 'Summer'],
        ], ['label']);
    }
}
