<?php

namespace Modules\User\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\FileAdder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasUser
{
    abstract public function addMedia(string|\Symfony\Component\HttpFoundation\File\UploadedFile $file): FileAdder;

    abstract public function hasMedia(string $collectionName = 'default', array $filters = []): bool;

    abstract public function getAvatarKey(): string;

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addAvatar(mixed $avatar): Media
    {
        return $this->addMedia($avatar)->toMediaCollection($this->getAvatarKey());
    }

    public function isMobileVerified(): bool
    {
        return ! is_null($this->mobile_verified_at);
    }

    public function unverifyMobile(): bool
    {
        return $this->forceFill([
            'mobile_verified_at' => null,
        ])->save();
    }
}
