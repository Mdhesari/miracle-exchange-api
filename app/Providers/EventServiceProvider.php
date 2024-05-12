<?php

namespace App\Providers;

use App\Events\User\UserInviterUpdated;
use App\Listeners\SendTicketMessageNotification;
use App\Listeners\SendTicketNotification;
use App\Listeners\SendUserNotifications;
use App\Listeners\SendWelcomeNotification;
use App\Listeners\User\SendInvitationRewardToUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Helpdesk\Events\TicketMessageCreated;
use Modules\Order\Events\AdminOrderTransactionCreated;
use Modules\Order\Events\OrderCreated;
use Modules\Order\Listeners\SendOrderNotifications;
use Modules\Order\Listeners\SendTransactionNotifications;
use Modules\Revenue\Listeners\CreateRevenueForInviter;
use Modules\User\Events\UserAuthorized;
use Modules\Wallet\Events\Transaction\TransactionVerified;
use Modules\Wallet\Events\TransactionReferenceUpdated;
use Modules\Wallet\Events\WalletTransaction;
use Modules\Wallet\Listeners\SendWalletNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class                   => [
            SendEmailVerificationNotification::class,
            SendWelcomeNotification::class,
        ],
        OrderCreated::class                 => [
            SendOrderNotifications::class,
        ],
        TransactionReferenceUpdated::class  => [
            SendTransactionNotifications::class,
        ],
        AdminOrderTransactionCreated::class => [
            SendTransactionNotifications::class,
        ],
        TransactionVerified::class          => [
            CreateRevenueForInviter::class,
        ],
        UserInviterUpdated::class           => [
            SendInvitationRewardToUser::class,
        ],
        UserAuthorized::class               => [
            SendUserNotifications::class,
        ],
        WalletTransaction::class            => [
            SendWalletNotification::class,
        ],
        TicketMessageCreated::class         => [
            SendTicketMessageNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
