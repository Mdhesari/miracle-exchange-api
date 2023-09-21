<?php

namespace Modules\User\Actions;

use App\Enums\UserStatus;
use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class UploadUserDocuments
{
    /**
     * @param User $user
     * @param array $data
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function __invoke(User $user, array $data)
    {
        if (isset($data['national_id_image'])) {
            $user->addNationalIdImage($data['national_id_image']);
        }

        if (isset($data['national_id_image_back'])) {
            $user->addNationalIdImage($data['national_id_image_back']);
        }

        if (isset($data['face_image'])) {
            $user->addFaceScanImage($data['face_image']);
        }

        $user->update([
            'status' => UserStatus::AdminPending->name,
        ]);
    }
}
