<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\AccountActivation;
use App\Enums\StatusEnum;

use App\Models\User;
use App\Models\EmailVerification;
class SendActivationEmail {

    public function execute(User $user)
    {
        if ($user->emailVerification == null) 
        {
            $user->emailVerification()->create([
                'email' => $user->email,
                'key' => generateEmailVerificationKey(),
                'status_id' => StatusEnum::PENDING->value
            ]);

            $user = User::find($user->id);

            Mail::to($user->email)->send(new AccountActivation($user, $user->emailVerification->key));
        }
    }
}