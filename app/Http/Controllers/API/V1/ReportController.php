<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GetSectionsByUserType;
use App\Services\GetGroupsByUserType;

use App\Models\Minute;
class ReportController extends Controller
{

    private $userGroup;
    private $userSection;

    public function __construct()
    {
        $this->userGroup = new GetGroupsByUserType();
        $this->userSection = new GetSectionsByUserType();
    }
    

    public function student($startDate, $endDate)
    {
        $user = request()->user();

        return [
            'data' => $this->userSection->execute($user, ['startAt' => $startDate, 'endAt' => $endDate])
        ];
    }

    public function group($capstoneType, $status)
    {
        $user = request()->user();

        return [
            'data' => $this->userGroup->execute($user, ['capstoneType' => $capstoneType, 'status' => $status])
        ];
    }

    public function section($startDate, $endDate)
    {
        $user = request()->user();

        return [
            'data' => $this->userSection->execute($user, ['startAt' => $startDate, 'endAt' => $endDate])
        ];
    }

    public function minute($type_id, $group_id)
    {
        $minute = Minute::query()
        ->with('userPrepared', 'contents', 'group.members', 'group.panels', 'schedule.type', 'schedule.status')
        ->where('group_id', $group_id)
        ->whereRelation('schedule', 'type_id', $type_id)
        ->first();

        return [
            'data' => $minute
        ];
    }
}
