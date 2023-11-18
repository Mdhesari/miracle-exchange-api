<?php

namespace Modules\Landing\Actions;

use Modules\Landing\Entities\Landing;

class CreateLanding
{
    public function __invoke(array $data)
    {
        $landing = Landing::create($data);

        if (isset($data['slug']) && $data['slug']) {
            $landing->slug = $data['slug'];
            $landing->save();
        }

        return $landing;
    }
}
