<?php

namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\OTPRequest;

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
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($otp)
    {
        $user = User::firstOrCreate([
            'mobile' => $otp->mobile
        ]);

        $token = auth()->login($user);

        return api()->success(null, [
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60
        ]);
    }

    private function otp()
    {
        return app(OTPController::class);
    }
}
