<?php

namespace Modules\Wallet\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Notification\Channels\DatabaseChannel;
use Modules\Order\Entities\Order;
use Modules\Wallet\Entities\Transaction;

class WalletNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Transaction $transaction
    )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return [DatabaseChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line($this->toArray($notifiable)['title']);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => __('notification.'.$this->transaction->type, [
                'symbol' => $this->getTransactionCurrency($this->transaction),
                'qua'    => $this->transaction->quantity
            ]),
        ];
    }

    private function getTransactionCurrency(Transaction $transaction)
    {
        $curr = '';

        if ($transaction->wallet) {

            $curr = $transaction->wallet->currency;
        }

        if ($transaction->transactionable instanceof Order) {

            $curr = $transaction->transactionable->market->symbol;
        }

        return $curr;
    }
}
