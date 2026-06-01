<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SitemapService;
use App\Models\Blog;

class SitemapController extends Controller
{
    protected SitemapService $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    /**
     * Generate main sitemap.xml
     */
    public function index()
    {
        $xml = $this->sitemapService->generate();
        
        return response($xml)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate artist sitemap
     */
    public function artists()
    {
        $xml = $this->sitemapService->generateArtistSitemap();
        
        return response($xml)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate artwork sitemap
     */
    public function artworks()
    {
        $xml = $this->sitemapService->generateArtworkSitemap();
        
        return response($xml)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate blog sitemap
     */
    public function blogs()
    {
        $xml = $this->sitemapService->generateBlogSitemap();
        
        return response($xml)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate collection sitemap
     */
    public function collections()
    {
        $xml = $this->sitemapService->generateCollectionSitemap();
        
        return response($xml)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate sitemap index
     */
    public function sitemapIndex()
    {
        $xml = $this->sitemapService->generateSitemapIndex();
        
        return response($xml)
            ->header('Content-Type', 'application/xml');
    }
}
