<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Setting;
class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::truncate();

        Setting::upsert([
            ['key' => 'section_days_span', 'label' => 'Section days span', 'value' => 5],
            ['key' => 'email_extension', 'label' => 'Email extension', 'value' => '@umindanao.edu.ph'],
            ['key' => 'student_submission_span', 'label' => 'Student submission span', 'value' => 5],
            ['key' => 'adviser_check_span', 'label' => 'Adviser check span', 'value' => 3],
            ['key' => 'panel_check_span', 'label' => 'Panel check span', 'value' => 3],
        ], ['key', 'label', 'value']);
    }
}
