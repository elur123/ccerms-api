<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Enums\StatusEnum;

use App\Models\Section;
use App\Models\Setting;
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
        ->whereDate('end_at', '>=', $date->sub($spanDays, 'days'))
        ->get();

        foreach ($sections as $key => $value) {
            
            $value->update([
                'status_id' => StatusEnum::DONE->value
            ]);
        }

        return ['message' => 'success'];
    }
}
