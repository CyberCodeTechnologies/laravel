<?php

namespace App\Services;

use App\Models\Artwork;
use App\Models\User;
use App\Models\Blog;
use App\Models\Collection;
use Illuminate\Support\Facades\Storage;

class SitemapService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('app.url');
    }

    /**
     * Generate complete sitemap
     */
    public function generate(): string
    {
        $xml = $this->generateXml();
        
        Storage::disk('public')->put('sitemap.xml', $xml);
        
        return Storage::disk('public')->url('sitemap.xml');
    }

    /**
     * Generate XML sitemap
     */
    protected function generateXml(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static pages
        $xml .= $this->addUrl($this->baseUrl, '1.0', 'daily');
        $xml .= $this->addUrl($this->baseUrl . '/artworks', '0.9', 'daily');
        $xml .= $this->addUrl($this->baseUrl . '/artists', '0.8', 'weekly');
        $xml .= $this->addUrl($this->baseUrl . '/marketplace', '0.7', 'daily');
        $xml .= $this->addUrl($this->baseUrl . '/blog', '0.6', 'weekly');

        // Artist pages
        $artists = \App\Models\User::where('role', 'artist')
            ->where('is_approved', true)
            ->whereHas('artworks', function ($query) {
                $query->where('status', 'approved');
            })
            ->get();

        foreach ($artists as $artist) {
            $url = $this->baseUrl . '/artists/' . ($artist->slug ?? $artist->id);
            $xml .= $this->addUrl($url, '0.8', 'weekly', $artist->updated_at);
        }

        // Artwork pages
        $artworks = \App\Models\Artwork::where('status', 'approved')->get();
        foreach ($artworks as $artwork) {
            $url = $this->baseUrl . '/artworks/' . $artwork->slug;
            $xml .= $this->addUrl($url, '0.9', 'weekly', $artwork->updated_at);
        }

        // Blog pages
        $blogs = \App\Models\Blog::where('is_published', true)
            ->where('published_at', '<=', now())
            ->get();
        foreach ($blogs as $blog) {
            $url = $this->baseUrl . '/blog/' . $blog->slug;
            $xml .= $this->addUrl($url, '0.6', 'monthly', $blog->updated_at);
        }

        // Collection pages
        $collections = \App\Models\Collection::where('is_active', true)->get();
        foreach ($collections as $collection) {
            $url = $this->baseUrl . '/collections/' . $collection->slug;
            $xml .= $this->addUrl($url, '0.5', 'monthly', $collection->updated_at);
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Add URL to sitemap
     */
    protected function addUrl(string $url, string $priority = '0.5', string $changefreq = 'monthly', ?\DateTime $lastmod = null): string
    {
        $xml = '  <url>' . "\n";
        $xml .= '    <loc>' . htmlspecialchars($url) . '</loc>' . "\n";
        
        if ($lastmod) {
            $xml .= '    <lastmod>' . $lastmod->format('Y-m-d') . '</lastmod>' . "\n";
        }
        
        $xml .= '    <changefreq>' . $changefreq . '</changefreq>' . "\n";
        $xml .= '    <priority>' . $priority . '</priority>' . "\n";
        $xml .= '  </url>' . "\n";

        return $xml;
    }

    /**
     * Generate artist sitemap
     */
    public function generateArtistSitemap(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $artists = \App\Models\User::where('role', 'artist')
            ->where('is_approved', true)
            ->whereHas('artworks', function ($query) {
                $query->where('status', 'approved');
            })
            ->get();

        foreach ($artists as $artist) {
            $url = $this->baseUrl . '/artists/' . ($artist->slug ?? $artist->id);
            $xml .= $this->addUrl($url, '0.8', 'weekly', $artist->updated_at);
        }

        $xml .= '</urlset>';

        Storage::disk('public')->put('sitemaps/artists.xml', $xml);
        
        return Storage::disk('public')->url('sitemaps/artists.xml');
    }

    /**
     * Generate artwork sitemap
     */
    public function generateArtworkSitemap(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $artworks = \App\Models\Artwork::where('status', 'approved')->get();
        foreach ($artworks as $artwork) {
            $url = $this->baseUrl . '/artworks/' . $artwork->slug;
            $xml .= $this->addUrl($url, '0.9', 'weekly', $artwork->updated_at);
        }

        $xml .= '</urlset>';

        Storage::disk('public')->put('sitemaps/artworks.xml', $xml);
        
        return Storage::disk('public')->url('sitemaps/artworks.xml');
    }

    /**
     * Generate blog sitemap
     */
    public function generateBlogSitemap(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $blogs = \App\Models\Blog::where('is_published', true)
            ->where('published_at', '<=', now())
            ->get();
        foreach ($blogs as $blog) {
            $url = $this->baseUrl . '/blog/' . $blog->slug;
            $xml .= $this->addUrl($url, '0.6', 'monthly', $blog->updated_at);
        }

        $xml .= '</urlset>';

        Storage::disk('public')->put('sitemaps/blogs.xml', $xml);
        
        return Storage::disk('public')->url('sitemaps/blogs.xml');
    }

    /**
     * Generate collection sitemap
     */
    public function generateCollectionSitemap(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $collections = \App\Models\Collection::where('is_active', true)->get();
        foreach ($collections as $collection) {
            $url = $this->baseUrl . '/collections/' . $collection->slug;
            $xml .= $this->addUrl($url, '0.5', 'monthly', $collection->updated_at);
        }

        $xml .= '</urlset>';

        Storage::disk('public')->put('sitemaps/collections.xml', $xml);
        
        return Storage::disk('public')->url('sitemaps/collections.xml');
    }

    /**
     * Generate sitemap index
     */
    public function generateSitemapIndex(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $sitemaps = [
            'sitemaps/artists.xml',
            'sitemaps/artworks.xml',
            'sitemaps/blogs.xml',
            'sitemaps/collections.xml',
        ];

        foreach ($sitemaps as $sitemap) {
            $url = $this->baseUrl . '/storage/' . $sitemap;
            $xml .= '  <sitemap>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($url) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . now()->format('Y-m-d') . '</lastmod>' . "\n";
            $xml .= '  </sitemap>' . "\n";
        }

        $xml .= '</sitemapindex>';

        Storage::disk('public')->put('sitemap_index.xml', $xml);
        
        return Storage::disk('public')->url('sitemap_index.xml');
    }
}
