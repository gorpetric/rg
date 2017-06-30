<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SocialLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware(['social', 'guest']);
    }

    public function redirect($service, Request $request)
    {
        return Socialite::driver($service)->redirect();
    }

    public function callback($service, Request $request)
    {
        $serviceUser = Socialite::driver($service)->user();

        $user = $this->getExistingUser($serviceUser, $service);

        if (!$user) {
            $user = User::create([
                'name' => $serviceUser->getName(),
                'email' => $serviceUser->getEmail(),
                'avatar' => $serviceUser->getAvatar(),
            ]);
        }

        if ($this->needsToCreateSocial($user, $service)) {
            $user->social()->create([
                'social_id' => $serviceUser->getId(),
                'service' => $service,
            ]);
        }

        $this->updateUserDataIfChanged($user, $serviceUser);

        Auth::login($user, false);

        return redirect()->intended();
    }

    protected function needsToCreateSocial(User $user, $service)
    {
        return !$user->hasSocialLinked($service);
    }

    protected function getExistingUser($serviceUser, $service)
    {
        return User::where('email', $serviceUser->getEmail())->orWhereHas('social', function ($q) use ($serviceUser, $service) {
            $q->where('social_id', $serviceUser->getId())->where('service', $service);
        })->first();
    }

    protected function updateUserDataIfChanged($user, $serviceUser)
    {
        if (
            $user->name !== $serviceUser->getName() ||
            $user->avatar !== $serviceUser->getAvatar()
        ) {
            $user->update([
                'name' => $serviceUser->getName(),
                'avatar' => $serviceUser->getAvatar(),
            ]);
        }
    }
}
