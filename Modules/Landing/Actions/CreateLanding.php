<?php

namespace Modules\Landing\Actions;

use Modules\Landing\Entities\Landing;

class CreateLanding
{
    public function __invoke(array $data)
    {
        return Landing::create($data);
    }
}
