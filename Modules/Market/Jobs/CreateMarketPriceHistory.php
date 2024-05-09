<?php

namespace Modules\Market\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Market\Entities\Market;

class CreateMarketPriceHistory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Market $market
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->market->prices()->create([
                'name'         => $this->market->name,
                'persian_name' => $this->market->persian_name,
                'symbol'       => $this->market->symbol,
                'symbol_char'  => $this->market->symbol_char,
                'price'        => $this->market->total_price,
                'date'         => $this->market->price_updated_at,
            ]);
        } catch (UniqueConstraintViolationException $e) {
            // ignore
        }
    }
}
