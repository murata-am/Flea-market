<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Http\Responses\RegisterResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use App\Actions\Fortify\UpdateUserProfileInformation;


class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::authenticateUsing(function (Request $request) {
        $loginRequest = app(LoginRequest::class);
        $loginRequest->merge($request->only('email', 'password'));
        $loginRequest->validateResolved();

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user &&\Illuminate\Support\Facades\Hash::check($request->password, $user->password)
        ) {
            return $user;
        }

        return null;
    });

        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });
    }
}
