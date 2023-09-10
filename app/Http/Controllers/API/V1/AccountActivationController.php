<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\StatusEnum;

use App\Models\EmailVerification;
use App\Models\User;
class AccountActivationController extends Controller
{
    public function activate($email, $key)
    {
        $update = EmailVerification::where('email', $email)
        ->where('key', $key)
        ->update([
            'status_id' => StatusEnum::APPROVED->value
        ]);

        if ($update) {
            User::where('email', $email)
            ->update([
                'status_id' => StatusEnum::APPROVED->value
            ]);
        }

        $url = config('app.frontend_url').'/activation?activated='.$update;

        return redirect()->away($url);
    }
}
