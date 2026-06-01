<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SitemapService;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate {type?}';
    protected $description = 'Generate XML sitemap for the website';

    protected SitemapService $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        parent::__construct();
        $this->sitemapService = $sitemapService;
    }

    public function handle()
    {
        $type = $this->argument('type');

        switch ($type) {
            case 'artists':
                $url = $this->sitemapService->generateArtistSitemap();
                $this->info("Artist sitemap generated: {$url}");
                break;
            case 'artworks':
                $url = $this->sitemapService->generateArtworkSitemap();
                $this->info("Artwork sitemap generated: {$url}");
                break;
            case 'blogs':
                $url = $this->sitemapService->generateBlogSitemap();
                $this->info("Blog sitemap generated: {$url}");
                break;
            case 'collections':
                $url = $this->sitemapService->generateCollectionSitemap();
                $this->info("Collection sitemap generated: {$url}");
                break;
            case 'index':
                $url = $this->sitemapService->generateSitemapIndex();
                $this->info("Sitemap index generated: {$url}");
                break;
            default:
                $url = $this->sitemapService->generate();
                $this->info("Main sitemap generated: {$url}");
                $this->info("\nTo generate specific sitemaps:");
                $this->info("  php artisan sitemap:generate artists");
                $this->info("  php artisan sitemap:generate artworks");
                $this->info("  php artisan sitemap:generate blogs");
                $this->info("  php artisan sitemap:generate collections");
                $this->info("  php artisan sitemap:generate index");
                break;
        }

        return Command::SUCCESS;
    }
}
