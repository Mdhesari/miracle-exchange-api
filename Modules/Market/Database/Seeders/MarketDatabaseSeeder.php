<?php

namespace Modules\Market\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Market\Entities\Market;
use Modules\Market\Enums\MarketStatus;

class MarketDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Market::create([
            'name'         => 'toman',
            'persian_name' => 'تومان',
            'country_code' => 'ir',
            'symbol_char'  => 'T',
            'symbol'       => 'toman',
            'price'        => 1,
            'status'       => MarketStatus::Enabled->name,
        ]);
    }
}
