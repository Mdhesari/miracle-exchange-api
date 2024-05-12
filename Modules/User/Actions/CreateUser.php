<?php

namespace Modules\User\Actions;

use App\Events\User\UserInviterUpdated;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Modules\User\Events\UserRestored;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class CreateUser
{
    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function __invoke(array $data)
    {
        $key = isset($data['mobile']) ? 'mobile' : 'email';
        $value = isset($data['mobile']) ? substr($data['mobile'], -10) : $data['email'];

        $user = User::withTrashed()->firstOrCreate([
            $key => $value,
        ], $data);

        if ($user->trashed()) {
            $user->restore();
            event(new UserRestored($user));
        } else {
            event(new Registered($user));
        }

        if (isset($data['inviter_code']) && is_null($user->inviter_id)) {
            $inviter = User::select('id')->where('invitation_code', $data['inviter_code'])->firstOrFail();

            $user->updateInviter($inviter->id);

            event(new UserInviterUpdated($user));
        }

        if (isset($data['avatar'])) {
            $user->addAvatar($data['avatar']);
        }

        if (isset($data['documents'])) {
            array_map(fn($document) => $user->addDocument($document), $data['documents']);
        }

        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return $user;
    }
}
