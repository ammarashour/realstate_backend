<?php

namespace App\Console\Commands;

use App\Config\ps_config;
use App\Http\Services\SiteMapService;
use Illuminate\Console\Command;
use Modules\Core\Entities\Item;
use Modules\Core\Entities\Category;
use Modules\Core\Entities\Subcategory;
use Modules\ItemPromotion\Entities\PaidItemHistory;
use Modules\Core\Entities\Vendor\Vendor;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Sitemap;

use Spatie\Sitemap\Tags\Url;

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
    protected $description = 'Automatically Generate an XML Sitemap';

    /**
     * Execute the console command.
     *
     * @return int
     */
    //return Command::SUCCESS;
    protected $siteMapService;

    public function handle()
    {
        $siteMapService = new SiteMapService();
        $siteMapService->itemMap();
        $siteMapService->categoryMap();
        $siteMapService->subcatMap();
        $siteMapService->vendorMap();
        $siteMapService->generateSitemap();
        $siteMapService->redirectToView();
        $siteMapService->blogMap();
        Log::info('Sitemap generation completed successfully.');

        return Command::SUCCESS;
    }
}
