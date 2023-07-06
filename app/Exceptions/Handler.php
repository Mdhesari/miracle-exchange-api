<?php

namespace App\Exceptions;

use HttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Exceptions\OTPRateLimitException;
use Shetabit\Multipay\Exceptions\PurchaseFailedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Convert an authentication exception into a response.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return \Illuminate\Http\RedirectResponse|JsonResponse|\Obiefy\API\APIResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? api(401, $exception->getMessage())
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param Request $request
     * @param ValidationException $exception
     * @return JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return api()->validation($exception->getMessage(), $exception->errors());
    }

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if (
            $e instanceof AuthorizationException ||
            ($e instanceof HttpException && $e->getStatusCode() == 403)
        ) {
            return api()->forbidden(empty($e->getMessage()) ? config('api.messages.forbidden') : $e->getMessage());
        }

        if ($e instanceof AuthenticationException) {
            return api(403, __('auth.unauthenticated'));
        }

        if ($e instanceof NotFoundHttpException) {
            return api()->notFound();
        }

        if ($e instanceof TokenExpiredException) {
            return api(401, $e->getMessage());
        }

        if ($e instanceof ValidationException) {
            return api()->validation(null, $e->validator->errors()->toArray());
        }

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            return api($e->getStatusCode() ?: 500, $e->getMessage());
        }

        if ($e instanceof OTPRateLimitException) {
            return api(429, $e->getMessage());
        }

        return $response;
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
