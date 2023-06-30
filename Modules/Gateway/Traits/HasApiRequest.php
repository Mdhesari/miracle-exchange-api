<?php

namespace Modules\Gateway\Traits;

use Illuminate\Support\Str;

trait HasApiRequest
{
    public abstract function method();

    private function getRules(array $data): array
    {
        if ( strtolower($this->method()) === 'put' ) {
            $data = array_map(function ($rule) {
                if ( is_array($rule) ) {
                    $rule[0] = 'nullable';
                } else {
                    $firstRule = substr($rule, 0, strpos($rule, '|'));

                    if ( $firstRule == 'required' ) {
                        $rule = 'nullable'.substr($rule, strpos($rule, '|'), strlen($rule));
                    }
                }

                return $rule;
            }, $data);
        }

        return $data;
    }
}
