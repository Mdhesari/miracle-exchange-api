<?php

namespace Modules\Landing\Actions;

use Modules\Landing\Entities\Landing;

class UpdateLanding
{
    public function __invoke(Landing $landing, array $data)
    {
        $landing->update($data);

        return $landing;
    }
}
