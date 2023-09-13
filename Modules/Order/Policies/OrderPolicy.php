<?php

namespace Modules\Order\Policies;

use App\Enums\UserStatus;
use App\Models\User;
use Modules\Order\Entities\Order;

class OrderPolicy
{
    public function show(User $user, Order $order): bool
    {
        return intval($order->user_id) === intval($user->id) || $user->can('orders');
    }

    public function update(User $user, Order $order): bool
    {
        return intval($order->user_id) === intval($user->id) || $user->can('orders');
    }

    public function delete(User $user, Order $order): bool
    {
        return intval($order->user_id) === intval($user->id) || $user->can('orders');
    }

    public function submit(User $user, Order $order): bool
    {
        return $user->status === UserStatus::Accepted->name || $us;
    }
}
