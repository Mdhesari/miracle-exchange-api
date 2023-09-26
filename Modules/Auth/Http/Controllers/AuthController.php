<?php

namespace Modules\Auth\Http\Controllers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Actions\SetupToken;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\OTPRequest;
use Modules\User\Actions\CreateUser;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login', 'sendOTP']);
    }

    public function sendOTP(OTPRequest $request)
    {
        $data = $request->validated();

        $this->otp()->send($data['mobile']);

        return api()->success();
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $otp = $this->otp()->verify($credentials['otp'], $credentials['mobile']);

        return $this->respondWithToken($otp);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return api()->success();
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return api()->success(null, [
            'access_token' => auth()->refresh(),
            'token_type'   => 'Bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param $otp
     * @return JsonResponse
     */
    protected function respondWithToken($otp)
    {
        $user = app(CreateUser::class)([
            'mobile' => $otp->mobile,
        ]);

        $token = app(SetupToken::class)($user);

        return api()->success(null, [
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @return mixed
     */
    public function google(): mixed
    {
        return Socialite::driver('google')->setScopes(['openid', 'email'])->stateless()->redirect();
    }

    /**
     * @param Request $request
     * @param CreateUser $createUser
     * @param SetupToken $setupToken
     * @return Application|RedirectResponse|Redirector
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function googleCallback(Request $request, CreateUser $createUser, SetupToken $setupToken): Application|RedirectResponse|Redirector
    {
        try {
            $user = Socialite::driver('google')->setScopes(['openid', 'email'])->stateless()->user();
        } catch (Exception $e) {
            throw $e;
        }

        $last_name = explode(' ', $user->name);
        $first_name = '';

        for ($i = 0; $i < count($last_name) - 1; $i++) {
            $first_name = $last_name[$i].' ';
        }

        $first_name = trim($first_name);

        $data = [
            'email'             => $user->email,
            'first_name'        => $first_name,
            'last_name'         => $last_name[count($last_name) - 1],
            'google_id'         => $user->id,
            'meta'              => [
                'google_avatar'          => $user->avatar,
                'google_avatar_original' => $user->avatar_original,
                'google_token'           => $user->token,
                'google_token_expire_in' => $user->expiresIn,
            ],
            'email_verified_at' => now(),
        ];

        $user = $createUser($data);

        $token = $setupToken($user, $request->only('platform'));

        return redirect(config('services.google.frontend_redirect').'?'.http_build_query(compact('token')));
    }

    private function otp()
    {
        return app(OTPController::class);
    }
}
