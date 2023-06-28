<?php

namespace Modules\Wallet\Console;

use Illuminate\Console\Command;
use Modules\Wallet\Actions\CreateWithdrawTransaction;
use Modules\Wallet\Entities\Transaction;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class WithdrawUserWallet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'wallet:withdraw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Withdraw form user wallet.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $args = $this->arguments();

        $transaction = app(CreateWithdrawTransaction::class)([
            'user_id'  => $args['user_id'],
            'quantity' => $args['quantity'],
            'status'   => Transaction::STATUS_VERIFIED,
        ]);

        $this->info(__('wallet::transaction.withdraw.success', [
            'user'           => $transaction->user->getSubject(),
            'quantity'       => $transaction->formatted_qua,
            'total_quantity' => $transaction->wallet->formatted_qua,
        ]));

        return 0;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['user_id', InputArgument::REQUIRED, 'The user id.'],
            ['quantity', InputArgument::REQUIRED, 'The transaction quantity.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            //
        ];
    }
}
