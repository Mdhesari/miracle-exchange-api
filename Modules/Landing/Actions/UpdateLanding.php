<?php

namespace Modules\Landing\Actions;

use Modules\Landing\Entities\Landing;

class UpdateLanding
{
    public function __invoke(Landing $landing, array $data)
    {
        $landing->update($data);

        if (isset($data['slug']) && $data['slug']) {
            $landing->slug = $data['slug'];
            $landing->save();
        }

        return $landing;
    }
}
