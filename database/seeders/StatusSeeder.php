<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Status;
class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::upsert([
            ['label' => 'Pending'],
            ['label' => 'Approved'],
            ['label' => 'Declined'],
            ['label' => 'Canceled'],
            ['label' => 'Minor'],
            ['label' => 'Major'],
            ['label' => 'Revised'],
            ['label' => 'Resched'],
        ], ['label']);
    }
}
