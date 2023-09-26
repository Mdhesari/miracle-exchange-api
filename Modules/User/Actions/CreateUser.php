<?php

namespace Modules\User\Actions;

use App\Models\User;
use Modules\User\Events\UserCreated;
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
            event(new UserCreated($user));
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
