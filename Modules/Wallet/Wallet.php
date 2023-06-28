<?php

namespace Modules\Wallet;

use NumberFormatter;
use Str;

class Wallet
{
    public static function user()
    {
        return app(config('wallet.users.model'));
    }

    public static function formatMoney($quantity, $currency = 'IRR'): string
    {
        $fmt = numfmt_create('fa_IR', NumberFormatter::CURRENCY);

        return (string)Str::of(numfmt_format_currency($fmt, $quantity, $currency));
    }
}
