<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Services\UpdateMilestoneStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Enums\StatusEnum;

use App\Models\Section;
use App\Models\Setting;
use App\Models\DefenseSchedule;
class CronController extends Controller
{
    
    public function checkSectionStudent()
    {
        $setting = Setting::query()
        ->where('key', 'section_days_span')
        ->first();

        $date = Carbon::now();
        $spanDays = intval($setting->value) ?? 5;

        $sections = Section::query()
        ->where('status_id', StatusEnum::ONGOING->value)
        ->whereDate('end_at', '<=', $date->sub($spanDays, 'days'))
        ->get();

        foreach ($sections as $key => $value) {

            foreach ($value->groups as $g_key => $g_value) {
                $isDone = $g_value->groupMilestone()
                ->where('capstone_type_id', $value->capstone_type_id)
                ->where('progress', '<', 100)
                ->first();

                if ($isDone != null) 
                {
                    $student_ids = $g_value->members->pluck('id');

                    $updated = $value->sectionStudent()
                    ->whereIn('user_id', $student_ids)
                    ->update([
                        'status_id' => StatusEnum::PENDING->value
                    ]);
                }
            }

            $students = $value->sectionStudent()
            ->whereDoesntHave('student.studentDetails.groupMember')
            ->update([
                'status_id' => StatusEnum::PENDING->value
            ]);
            
            $value->update([
                'status_id' => StatusEnum::DONE->value
            ]);
        }

        return ['message' => 'success'];
    }

    public function checkGroupDefenseSchedule(UpdateMilestoneStatus $milestoneStatus)
    {
        DefenseSchedule::where('status_id', StatusEnum::PENDING->value)
        ->whereDate('end_at', '<', Carbon::now())
        ->update([
            'status_id' => StatusEnum::CANCELED->value
        ]);

        $setting = Setting::query()
        ->where('key', 'student_submission_span')
        ->first();

        $spanDays = intval($setting->value) ?? 5;

        $date = Carbon::now();
        $schedules = DefenseSchedule::query()
        ->with('group')
        ->where('status_id', StatusEnum::PENDING->value)
        ->whereDate('start_at', '>=', $date->sub($spanDays, 'days'))
        ->get();

        foreach ($schedules as $key => $value) {
            $milestoneStatus->execute($value);
        }

        return ['message' => 'success'];
    }
}
