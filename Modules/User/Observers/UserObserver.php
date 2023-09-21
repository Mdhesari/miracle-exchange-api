<?php

namespace Modules\User\Observers;

use App\Enums\UserGender;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Str;
use Modules\User\Actions\DeleteUserResources;

class UserObserver
{
    public function creating(User $user)
    {
        if (! $user->gender) {
            $user->gender = UserGender::Male->name;
        }

        if (! $user->status) {
            $user->status = UserStatus::Pending->name;
        }

        if (! $user->invitation_code) {
            $user->invitation_code = $this->getInvitationCode();
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

    private function getInvitationCode(): string
    {
        $code = config('app.name').'_'.Str::random(6);

        return User::where('invitation_code', $code)->exists() ? $this->getInvitationCode() : $code;
    }
}
