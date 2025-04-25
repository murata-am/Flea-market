<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    public function update(User $user, array $input): void
    {
        $profileRequest = new ProfileRequest();
        $profileValidator = Validator::make($input, $profileRequest->rules());
        $profileValidator->validate();

        $addressRequest = new AddressRequest();
        $addressValidator = Validator::make($input, $addressRequest->rules());
        $addressValidator->validate();

        if (isset($input['image'])) {
            $imagePath = $input['image']->store('profile_images', 'public');
            $user->profile_image = $imagePath;
        }

        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'address' => $input['address'],
            'postal_code' => $input['postal_code'],
            'building' => $input['building'],
        ])->save();
    }

    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
