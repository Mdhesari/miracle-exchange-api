<?php

namespace Modules\User\Observers;

use App\Enums\UserGender;
use App\Models\User;
use Modules\User\Actions\DeleteUserResources;

class UserObserver
{
    public function creating(User $user)
    {
        if (! $user->gender) {
            $user->gender = UserGender::Male;
        }
    }

    public function updating(User $user)
    {
        $original = $user->getOriginal('mobile');

        if ($original && $original != $user->mobile && $user->isMobileVerified()) {
            $user->unverifyMobile();
        }
    }

    public function deleting(User $user)
    {
        app(DeleteUserResources::class)($user);
    }
}
