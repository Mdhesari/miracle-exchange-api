<?php

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

beforeEach(fn() => $this->actingAs());

it('can user request for authorization', function () {
    Storage::fake();

    $user = Auth::user();

    $response = $this->post(route('users.authorize', $user), [
        'national_id_image' => UploadedFile::fake()->image('national_id.jpg'),
        'face_image'        => UploadedFile::fake()->image('face_scan.jpg'),
    ]);

    $response->dump()->assertSuccessful();

    $user->refresh();

    $this->assertNotNull($user->getFirstMedia(User::MEDIA_NATIONAL_ID));
    $this->assertNotNull($user->getFirstMedia(User::MEDIA_FACE_SCAN));
});

it('can admin authorize user', function () {
    givePerm('users');

    $response = $this->put(route('users.authorize.accept', $user = User::factory()->create()));

    $response->assertSuccessful();

    $this->assertEquals(2, $user->refresh()->level);
    $this->assertEquals(UserStatus::Accepted->name, $user->status);
});


it('can admin reject user', function () {
    givePerm('users');

    $response = $this->put(route('users.authorize.reject', $user = User::factory()->create()));

    $response->assertSuccessful();

    $this->assertEquals(1, $user->refresh()->level);
    $this->assertEquals(UserStatus::Rejected->name, $user->status);
});
