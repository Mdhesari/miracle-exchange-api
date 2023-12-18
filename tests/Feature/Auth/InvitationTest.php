<?php

use App\Events\User\UserInviterUpdated;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Modules\Auth\Entities\OTP;

it('can register user with invitation code', function () {
    $inviter = User::factory()->create();
    $user = User::factory()->create([
        'mobile' => '9128209090',
    ]);

    Event::fake(UserInviterUpdated::class);

    OTP::create([
        'mobile'      => $user->mobile,
        'otp'         => $otp = '12345678',
        'otp_sent_at' => now(),
    ]);

    $response = $this->post(route('auth.login'), [
        'inviter_code' => $inviter->invitation_code,
        'mobile'       => $user->mobile,
        'otp'          => $otp,
    ]);

    $response->assertSuccessful();

    Event::assertDispatched(UserInviterUpdated::class);
});

it('cannot register user who has already been invited by someone else with invitation code', function () {
    $inviter = User::factory()->create();
    $user = User::factory()->create([
        'mobile'     => '9128209090',
        'inviter_id' => $inviter->id,
    ]);

    Event::fake(UserInviterUpdated::class);

    OTP::create([
        'mobile'      => $user->mobile,
        'otp'         => $otp = '12345678',
        'otp_sent_at' => now(),
    ]);

    $response = $this->post(route('auth.login'), [
        'inviter_code' => $inviter->invitation_code,
        'mobile'       => $user->mobile,
        'otp'          => $otp,
    ]);

    $response->assertSuccessful();

    Event::assertNotDispatched(UserInviterUpdated::class);
});
