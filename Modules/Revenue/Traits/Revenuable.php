<?php

namespace Modules\Revenue\Traits;

use Modules\Revenue\Entities\Revenue;

trait Revenuable
{
    abstract public function morphMany($related, $name, $type = null, $id = null, $localKey = null);

    public function revenues()
    {
        return $this->morphMany(Revenue::class, 'revenuable');
    }
}
