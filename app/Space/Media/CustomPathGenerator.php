<?php

namespace App\Space\Media;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CustomPathGenerator
{
    /*
         * Get the path for the given media, relative to the root storage path.
         */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media).'/';
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media).'/conversions/';
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media).'/responsive-images/';
    }

    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        $prefix = config('media-library.prefix', '');

        if ($prefix !== '') {
            return $prefix.'/'.$this->getCustomPath($media);
        }

        return $this->getCustomPath($media);
    }

    private function getCustomPath(Media $media): string
    {
        return md5($media->getKey());
    }

}
