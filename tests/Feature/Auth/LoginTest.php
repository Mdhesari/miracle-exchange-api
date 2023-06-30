<?php

it('can send otp', function () {
    $response = $this->post(route('auth.otp.send'), [
        'mobile' => '9128177871',
    ]);

    $response->assertSuccessful();
});
