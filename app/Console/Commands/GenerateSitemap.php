<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Landing\Entities\Landing;
use Modules\Market\Entities\Market;
use Modules\Market\Enums\MarketStatus;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates sitemap for url';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $markets = array_map(fn($market_id) => sprintf('https://sarrafi.app/market/%s', $market_id), Market::whereStatus(MarketStatus::Enabled->name)->pluck('id')->toArray());

        Sitemap::create()
            ->add($markets)
            ->writeToFile(public_path($markets_path = 'sitemaps/markets.xml'));

        $landing = array_map(fn($landing_id) => sprintf('https://sarrafi.app/currency/%s', $landing_id), Landing::pluck('slug')->toArray());

        Sitemap::create()
            ->add($landing)
            ->writeToFile(public_path($currencies_path = 'sitemaps/currencies.xml'));

        Sitemap::create()
            ->add('https://sarrafi.app')
            ->add('https://sarrafi.app/about-us')
            ->add('https://sarrafi.app/contact-us')
            ->add('https://sarrafi.app/usage-rules')
            ->add('https://sarrafi.app/frequently-questions')
            ->add('https://sarrafi.app/support')
            ->add('https://sarrafi.app/transactions')
            ->add('https://sarrafi.app/transfer')
            ->writeToFile(public_path($pages_path = 'sitemaps/pages.xml'));


        SitemapIndex::create()
            ->add(url($pages_path))
            ->add(url($markets_path))
            ->add(url($currencies_path))
            ->writeToFile(public_path('sitemap.xml'));
    }
}
