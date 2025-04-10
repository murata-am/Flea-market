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
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        $profileRequest = new ProfileRequest();
        $profileValidator = Validator::make($input, $profileRequest->rules());
        $profileValidator->validate();  // バリデーション実行

        // 住所リクエストのバリデーション
        $addressRequest = new AddressRequest();
        $addressValidator = Validator::make($input, $addressRequest->rules());
        $addressValidator->validate();  // バリデーション実行

        // プロフィール画像がアップロードされている場合
        if (isset($input['image'])) {
            // 画像ファイルの保存
            $imagePath = $input['image']->store('profile_images', 'public');  // public ディスクに保存

            // 画像パスをユーザー情報に保存
            $user->profile_image = $imagePath;
        }

        // ユーザー情報の更新
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'address' => $input['address'],  // 住所を追加
            'postal_code' => $input['postal_code'],  // 郵便番号を追加
            'building' => $input['building'],  // 建物名を追加
        ])->save();
    }
}

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
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
