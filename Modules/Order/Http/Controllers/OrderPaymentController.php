<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Order\Http\Requests\OrderPaymentRequest;

class OrderPaymentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(OrderPaymentRequest $request)
    {
        //
    }
}
