<?php

namespace Modules\Market\Actions;

use Mdhesari\LaravelQueryFilters\Abstract\BaseQueryFilters;
use Modules\Market\Enums\MarketStatus;

class ApplyMarketQueryFilters extends BaseQueryFilters
{
    public function __invoke($query, array $data)
    {
        parent::__invoke($query, $data);

        if (! $this->user() || $this->user()?->cannot('markets')) {
            $query->where('status', MarketStatus::Enabled->name);
        } else {
            if (isset($data['status']))
                $query->where('status', $data['status']);
        }

        if ($this->user()) {
            if (isset($data['bookmarked']) && $data['bookmarked']) {
                $query->whereHas('bookmarks', fn($query) => $query->whereUserId($this->user()->id));
            }
        }

        if (isset($data['is_crypto'])) {
            $query->whereIsCrypto(boolval($data['is_crypto']));
        }

        return $query;
    }
}
