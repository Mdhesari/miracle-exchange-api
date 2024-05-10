<?php

namespace Modules\Wallet\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Wallet\Entities\CryptoNetwork;

class WalletDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        CryptoNetwork::create([
            'name'      => 'trc20',
            'fee'       => 1,
            'is_active' => true,
        ]);
    }
}
