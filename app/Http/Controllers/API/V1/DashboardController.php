<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Enums\CapstoneTypeEnum;

use App\Models\Section;
use App\Models\SectionStudent;
use App\Models\SectionGroup;
use App\Models\User;
use App\Models\Group;
use App\Models\Course;
class DashboardController extends Controller
{
    
    public function index(Request $request)
    {
        $user = $request->user();
        $data = (object)[];
        
        if ($user->role_id === RoleEnum::STUDENT->value) 
        {
            
        }
        else
        {
            $data->studentsCount = 0;
            $data->rcCount = 0;
            $data->stCount = 0;
            $data->panelsCount = 0;
            $data->adviserCount = 0;
            $data->researchArchivesCount = 0;
            $data->coursesCounts = 0;
            $data->groupsCounts = 0;
            $data->capstoneOne = [ 'label' => [], 'data' => []];
            $data->capstoneTwo = [ 'label' => [], 'data' => []];


            $data->rcCount = User::where('role_id', RoleEnum::RESEARCH_COORDINATOR->value)
            ->approved()
            ->count();

            $data->stCount = User::where('can_teach', 1)
            ->approved()
            ->count();

            $data->panelsCount = User::where('can_panel', 1)
            ->approved()
            ->count();

            $data->adviserCount = User::where('can_advise', 1)
            ->approved()
            ->count();

            $data->courseCounts = Course::count();

            switch ($user->role_id) {

                case RoleEnum::SUBJECT_TEACHER->value:

                    $studentsCount = SectionStudent::whereRelation('section', 'user_id', $user->id)
                    ->whereRelation('student', 'status_id', StatusEnum::APPROVED->value)
                    ->get()
                    ->groupBy('user_id')
                    ->count();

                    $groupsCounts = SectionGroup::whereRelation('section', 'user_id', $user->id)
                    ->get()
                    ->groupBy('group_id')
                    ->count();

                    $data->studentsCount = $studentsCount;
                    $data->groupsCounts = $groupsCounts;
                    break;
                
                case RoleEnum::ADVISER->value:
                    
                    break;

                case RoleEnum::PANEL->value:
                    
                    break;

                default:

                    $data->studentsCount = User::query()
                    ->student()
                    ->approved()
                    ->count();

                    $data->groupsCounts = Group::count();

                    $capstoneOne = Group::where('is_done', 0)
                    ->with('groupMilestone')
                    ->where('capstone_type_id', CapstoneTypeEnum::ONE->value)
                    ->get()
                    ->map(function($group) {
                        $onePercent = $group->groupMilestone()
                        ->whereRelation('milestone', 'capstone_type_id', CapstoneTypeEnum::ONE->value)
                        ->first();

                        return [
                            'id' => $group->id,
                            'group_name' => $group->group_name,
                            'capstoneOnePercent' => $onePercent->progress
                        ];
                    });

                    $data->capstoneOne['label'] = $capstoneOne->pluck('group_name');
                    $data->capstoneOne['data'] = $capstoneOne->pluck('capstoneOnePercent');

                    $capstoneTwo = Group::where('is_done', 0)
                    ->with('groupMilestone')
                    ->where('capstone_type_id', CapstoneTypeEnum::TWO->value)
                    ->get()
                    ->map(function($group) {
                        $onePercent = $group->groupMilestone()
                        ->whereRelation('milestone', 'capstone_type_id', CapstoneTypeEnum::TWO->value)
                        ->first();

                        return [
                            'id' => $group->id,
                            'group_name' => $group->group_name,
                            'capstoneOnePercent' => $onePercent->progress
                        ];
                    });

                    $data->capstoneTwo['label'] = $capstoneTwo->pluck('group_name');
                    $data->capstoneTwo['data'] = $capstoneTwo->pluck('capstoneOnePercent');
                    break;
            }
        }

        return response()->json([
            'data' => $data
        ], 200);
    }
}
