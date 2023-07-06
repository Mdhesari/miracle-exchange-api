<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Entities\OTP;
use Modules\Auth\Exceptions\OTPRateLimitException;
use Modules\Auth\Jobs\SendSMS;

class OTPController extends Controller
{
    /**
     * @var int
     */

    protected int $delayBetweenEachOTP;

    /**
     * @var int
     */

    protected int $otpValidationTime;

    /**
     * @var int
     */

    protected int $tokenLength;

    /**
     * OTPController constructor.
     */

    public function __construct()
    {
        $this->delayBetweenEachOTP = config('auth.otp-delay') ?: 30;

        $this->otpValidationTime = config('auth.otp-expires-after') ?: 120;

        $this->tokenLength = config('auth.otp-token-length') ?: 6;
    }

    /**
     * @param int $token
     * @param string $mobile
     * @return mixed
     * @throws ValidationException
     */

    public function verify(int $token, string $mobile)
    {
        /**
         * Try to find the last the unverified otp we send to the entered mobile.
         */

        $otp = OTP::whereMobile($mobile)->unverified()->latest()->first();

        /**
         * Validate entered otp to the OTP we have in database.
         */

        if (! $otp || $token != $otp->otp || now()->addSeconds($this->otpValidationTime) < $otp->otp_sent_at) {

            throw ValidationException::withMessages([
                'otp' => __('responses.auth.otp.wrongToken'),
            ]);

        }

        $otp->update([
            'otp_is_verified' => true
        ]);

        return $otp;
    }

    /**
     * @param string $mobile
     * @throws \Exception
     */

    public function send(string $mobile)
    {
        /**
         * Let's start by checking last time we send a token.
         */

        $otpExist = OTP::whereMobile($mobile)->unverified();

        if ($otpExist->exists()) {

            $lastSentTokenDiffInSeconds = now()->diffInSeconds($otpExist->latest()->first()->otp_sent_at);

            if ($lastSentTokenDiffInSeconds < $this->delayBetweenEachOTP) {

                throw new OTPRateLimitException(__('responses.auth.otp.pleaseWait', ['seconds' => $this->delayBetweenEachOTP - $lastSentTokenDiffInSeconds]), 429);

            }

        }

        /**
         * Generate token.
         */

        $otp = $this->tokenGenerator($this->tokenLength);

        /**
         * Store token in database.
         */

        OTP::create([
            'mobile'      => $mobile,
            'otp'         => $otp,
            'otp_sent_at' => now(),
        ]);

        /**
         * Prepare necessary data for sending sms.
         */

        $data = [
            $otp
        ];

        /**
         * Dispatch send sms job.
         */

        //SendSMS::dispatch($mobile, "verifyOtp", $data);
    }

    /**
     * @param int $length
     * @param string|null $table
     * @param string|null $column
     * @return int
     */

    public function tokenGenerator(int $length = 6, string $table = null, string $column = null): int
    {
        return 12345678;
        $token = mt_rand(pow(10, $length - 1), pow(10, $length) - 1);

        if ($table && $column) {

            if (Schema::hasColumn($table, $column)) {

                $isExists = DB::table($table)->where($column, $token)->exists();

                if ($isExists) {

                    $this->tokenGenerator($length, $table, $column);

                }

            }

        }

        return $token;
    }
}
