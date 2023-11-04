<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Enums\CapstoneTypeEnum;
use Carbon\Carbon;
use App\Http\Resources\DefenseScheduleResource;

use App\Models\Section;
use App\Models\SectionStudent;
use App\Models\SectionGroup;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupAdviser;
use App\Models\GroupPanel;
use App\Models\GroupMember;
use App\Models\Course;
use App\Models\DefenseSchedule;
use App\Models\BoardSubmission;
use App\Models\ResearchArchive;
class DashboardController extends Controller
{
    
    public function index(Request $request)
    {
        $user = $request->user();
        $data = (object)[];
        
        if ($user->role_id === RoleEnum::STUDENT->value) 
        {
            $data->submissionsCounts = 0;
            $data->schedules = [];
            $data->capstoneOne = ['label' => ['Done', 'Ongoing'], 'data' => [0, 100]];
            $data->capstoneTwo = ['label' => ['Done', 'Ongoing'], 'data' => [0, 100]];

            $group = Group::whereRelation('members', 'user_id', $user->id)
            ->with('groupMilestone')
            ->first();

            if ($group != null) {
                $data->submissionsCounts = BoardSubmission::whereRelation('board', 'group_id', $group->id)
                ->count();

                $milestoneOne = $group->groupMilestone()
                ->where('capstone_type_id', CapstoneTypeEnum::ONE->value)
                ->first();

                $milestoneTwo = $group->groupMilestone()
                ->where('capstone_type_id', CapstoneTypeEnum::TWO->value)
                ->first();

                $data->capstoneOne['data'][0] = $milestoneOne->progress;
                $data->capstoneOne['data'][1] = 100 - doubleval($milestoneOne->progress);

                $data->capstoneTwo['data'][0] = $milestoneTwo->progress;
                $data->capstoneTwo['data'][1] = 100 - doubleval($milestoneTwo->progress);
            }
            
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
            $data->capstoneOne = ['label' => [], 'data' => []];
            $data->capstoneTwo = ['label' => [], 'data' => []];
            $data->schedules = [];


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

            $data->coursesCounts = Course::count();

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
                    $studentsCount = GroupMember::whereRelation('student', 'status_id', StatusEnum::APPROVED->value)
                    ->whereRelation('group.advisers', 'user_id', $user->id)
                    ->count();

                    $groupsCounts = GroupAdviser::where('user_id', $user->id)
                    ->count();

                    $data->studentsCount = $studentsCount;
                    $data->groupsCounts = $groupsCounts;
                    break;

                case RoleEnum::PANEL->value:
                    $studentsCount = GroupMember::whereRelation('student', 'status_id', StatusEnum::APPROVED->value)
                    ->whereRelation('group.panels', 'user_id', $user->id)
                    ->count();

                    $groupsCounts = GroupPanel::where('user_id', $user->id)
                    ->count();

                    $data->studentsCount = $studentsCount;
                    $data->groupsCounts = $groupsCounts;                    
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

        $data->researchArchivesCount = ResearchArchive::count();

        $data->schedules = DefenseScheduleResource::collection(DefenseSchedule::query()
        ->with('group', 'type', 'status')
        ->whereDate('start_at', Carbon::now())
        ->get());

        return response()->json([
            'data' => $data
        ], 200);
    }
}
