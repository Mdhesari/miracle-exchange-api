<?php

namespace Modules\Market\Console;

use Illuminate\Console\Command;
use Modules\Market\Entities\Market;
use Modules\Market\Jobs\CreateMarketPriceHistory;
use Psy\Command\ExitCommand;

class UpdateMarketFromNavasan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'market:update-navasan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update markets from navasan.';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $data = $this->navasan()->getLatest();

        $bar = $this->output->createProgressBar(count($data));

        $marketsName = json_decode(file_get_contents(__DIR__.'/markets.json'), true);

        $bar->start();

        foreach ($data as $key => $market) {
            if (! isset($market['value']) || ! $market['value']) {

                continue;
            }

            $marketName = $marketsName[$key] ?? null;

            $marketModel = Market::firstOrCreate([
                'symbol' => $key,
            ], [
                'persian_name'     => is_array($marketName) ? $marketName['title'] : null,
                'country_code'     => is_array($marketName) ? $marketName['country_code'] : null,
                'symbol_char'      => is_array($marketName) ? $marketName['symbol'] : null,
                'price'            => $market['value'],
                'price_updated_at' => verta()->parse($market['date'])->toCarbon(),
            ]);

            if (! $marketModel->wasRecentlyCreated) {
                $marketModel->update([
                    'price'            => $market['value'],
                    'price_updated_at' => verta()->parse($market['date'])->toCarbon(),
                ]);
            }

            dispatch(new CreateMarketPriceHistory($marketModel->fresh()));

            $bar->advance();
        }

        $bar->finish();

        return ExitCommand::SUCCESS;
    }

    private function navasan()
    {
        return app('navasan');
    }
}
