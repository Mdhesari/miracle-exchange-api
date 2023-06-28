<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, WithFaker;

    protected $seed = true;

    protected $defaultHeaders = [
        'accept' => 'application/json',
    ];

    public function actingAs(?UserContract $user = null, $guard = null)
    {
        $token = auth()->login($user = $user ?: User::factory()->create());

        $this->withHeader('Authorization', "Bearer {$token}");

        parent::actingAs($user);

        return $this;
    }
}
