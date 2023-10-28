<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Course;
use App\Models\Milestone;
class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $one = Milestone::create([
            'title' => 'Milestone One',
            'description' => 'Milestone for capstone one',
            'capstone_type_id' => 1
        ]);

        $two = Milestone::create([
            'title' => 'Milestone Two',
            'description' => 'Milestone for capstone two',
            'capstone_type_id' => 2
        ]);


        $one->milestoneList()->create([
            'title' => 'Step One',
            'description' => 'Submit chapter 1 to 3',
            'percent' => 50,
            'order_by' => 1,
            'adviser_first' => true,
            'has_adviser' => true,
            'has_panel' => true,
        ]);

        $one->milestoneList()->create([
            'title' => 'Step Two',
            'description' => 'Submit system prototype',
            'percent' => 50,
            'order_by' => 2,
            'adviser_first' => true,
            'has_adviser' => true,
            'has_panel' => true,
        ]);

        $two->milestoneList()->create([
            'title' => 'Step One',
            'description' => 'Submit chapter 4 to 5',
            'percent' => 50,
            'order_by' => 1,
            'adviser_first' => true,
            'has_adviser' => true,
            'has_panel' => true,
        ]);

        $two->milestoneList()->create([
            'title' => 'Step Two',
            'description' => 'Submit full blown system',
            'percent' => 50,
            'order_by' => 2,
            'adviser_first' => true,
            'has_adviser' => true,
            'has_panel' => true,
        ]);

        Course::create([
            'key' => 'IT',
            'label' => 'BSIT',
            'milestone_one' => $one->id,
            'milestone_two' => $two->id,
        ]);

        Course::create([
            'key' => 'CS',
            'label' => 'BSCS',
            'milestone_one' => $one->id,
            'milestone_two' => $two->id,
        ]);
    }
}
