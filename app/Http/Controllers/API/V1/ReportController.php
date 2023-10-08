<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GetSectionsByUserType;

class ReportController extends Controller
{

    private $userSection;

    public function __construct()
    {
        $this->userSection = new GetSectionsByUserType();
    }
    

    public function student($startDate, $endDate)
    {
        $user = request()->user();

        return [
            'data' => $this->userSection->execute($user, ['startAt' => $startDate, 'endAt' => $endDate])
        ];
    }

    public function group($startDate, $endDate)
    {
        $user = request()->user();

        return [
            'data' => $this->userSection->execute($user, ['startAt' => $startDate, 'endAt' => $endDate])
        ];
    }

    public function section($startDate, $endDate)
    {
        $user = request()->user();

        return [
            'data' => $this->userSection->execute($user, ['startAt' => $startDate, 'endAt' => $endDate])
        ];
    }
}
