<?php

namespace Modules\Market\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Market\Entities\Market;
use Psy\Command\ExitCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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

        $bar->start();

        foreach ($data as $key => $market) {
            try {
                Market::firstOrCreate([
                    'symbol' => $key,
                ], [
                    'price'            => $market['value'],
                    'price_updated_at' => verta()->parse($market['date'])->toCarbon(),
                ]);
            } catch (\Exception $e) {
                Log::error($e);
            }

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
