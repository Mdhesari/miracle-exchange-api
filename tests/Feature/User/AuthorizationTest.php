<?php

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
