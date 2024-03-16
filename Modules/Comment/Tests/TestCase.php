<?php

namespace Modules\Comment\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, WithFaker;

    protected $seed = true;

    protected $defaultHeaders = [
        'accept' => 'application/json',
    ];
}
