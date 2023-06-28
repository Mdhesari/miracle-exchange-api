<?php

namespace Modules\User\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Events\UserOnboarded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use function event;

class OnboardUser
{
    /**
     * @param User $user
     * @param array $data
     * @return User
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws \Throwable
     */
    public function __invoke(User $user, array $data): User
    {
        if ( isset($data['avatar']) ) {
            $user->addAvatar($data['avatar']);
        }

        if ( isset($data['documents']) ) {
            $media = array_map(fn($media) => is_object($media) ? $user->addDocument($media)->id : $media, $data['documents']);

            $this->deleteAllUserMediaExcept($user, $media);
        }

        $user->update($data);

        if ( isset($data['roles']) ) {
            $user->roles()->sync($data['roles']);
        }

        if (
            isset($data['password']) &&
            isset($data['old_password'])
        ) {
            throw_if(! Hash::check($data['old_password'], $user->password), ValidationException::withMessages([
                'old_password' => __('auth.password')
            ]));

            $user->changePassword($data['password']);
        }

        event(new UserOnboarded($user));

        return $user;
    }

    private function deleteAllUserMediaExcept(User $user, array $media, string $collection = 'documents')
    {
        $user->media()->where('collection_name', $collection)->whereNotIn('id', $media)->each(fn($media) => $media->delete());
    }
}
