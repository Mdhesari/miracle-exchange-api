<?php

namespace Modules\Wallet\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Modules\Wallet\Entities\Transaction;

class TransactionsExport implements FromCollection, WithMapping, ShouldAutoSize, WithHeadings
{
    public function __construct(
        private $query = null
    )
    {
        //
    }

    public function map($transaction): array
    {
        return [
            $transaction->payer,
            $transaction->receiver,
            $transaction->transactionable?->title,
            $transaction->quantity,
            $transaction->status_trans,
            $transaction->type_trans,
            verta($transaction->created_at),
            $transaction->reference,
            $transaction->gateway,
            $transaction->callback_url,
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->query?->get() ?: Transaction::all();
    }

    public function headings(): array
    {
        return [
            __('wallet::transaction.properties.payer'),
            __('wallet::transaction.properties.receiver'),
            __('wallet::transaction.properties.title'),
            __('wallet::transaction.properties.quantity'),
            __('wallet::transaction.properties.status'),
            __('wallet::transaction.properties.type'),
            __('wallet::transaction.properties.created_at'),
            __('wallet::transaction.properties.reference'),
            __('wallet::transaction.properties.gateway'),
            __('wallet::transaction.properties.callback_url'),
        ];
    }
}
