<?php

namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Wallet\Entities\Transaction;

class StatisticsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return api()->success(null, [
            'items' => [
                'orders'      => $this->getOrdersCount(),
                'users'       => $this->getUsersCount(),
                'received'    => $this->getReceivedTransactionsQuantity(),
                'transferred' => $this->getTransferredTransactionsQuantity(),
            ],
        ]);
    }

    private function getOrdersCount()
    {
        return cache()->rememberForever('count::orders', fn() => Order::count());
    }

    private function getUsersCount()
    {
        return cache()->rememberForever('count::users', fn() => User::count());
    }

    private function getReceivedTransactionsQuantity()
    {
        return cache()->rememberForever('transactions::received', fn() => Transaction::where('type', Transaction::TYPE_WITHDRAW)->sum('quantity'));
    }

    private function getTransferredTransactionsQuantity()
    {
        return cache()->rememberForever('transactions::transferred', fn() => Transaction::where('type', Transaction::TYPE_DEPOSIT)->sum('quantity'));
    }
}
