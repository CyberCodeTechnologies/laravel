<?php

namespace App\Helpers;

use App\Models\Artwork;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class SchemaHelper
{
    /**
     * Generate organization schema
     */
    public static function organization(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'PanchiGallery',
            'url' => config('app.url'),
            'logo' => asset('images/logo.png'),
            'description' => 'Myanmar\'s premier art gallery featuring contemporary and traditional artworks from talented artists across Myanmar.',
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'MM',
                'addressRegion' => 'Yangon',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+95-9-123456789',
                'contactType' => 'customer service',
            ],
            'sameAs' => [
                'https://www.facebook.com/panchigallery',
                'https://www.instagram.com/panchigallery',
            ],
        ];
    }

    /**
     * Generate artwork schema
     */
    public static function artwork(Artwork $artwork): array
    {
        $artist = $artwork->artist;
        $imageUrl = $artwork->images[0] ?? null;

        return [
            '@context' => 'https://schema.org',
            '@type' => 'VisualArtwork',
            'name' => $artwork->title,
            'description' => $artwork->description,
            'image' => $imageUrl ? asset('storage/' . $imageUrl) : null,
            'creator' => [
                '@type' => 'Person',
                'name' => $artist->full_name,
                'url' => route('artists.show', $artist->slug ?? $artist->id),
            ],
            'artform' => $artwork->medium,
            'height' => self::parseHeight($artwork->dimensions),
            'width' => self::parseWidth($artwork->dimensions),
            'artMedium' => $artwork->medium,
            'dateCreated' => $artwork->year,
            'offers' => [
                '@type' => 'Offer',
                'price' => $artwork->price,
                'priceCurrency' => $artwork->currency,
                'availability' => $artwork->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => route('artworks.show', $artwork->slug),
            ],
            'url' => route('artworks.show', $artwork->slug),
        ];
    }

    /**
     * Generate artist schema
     */
    public static function artist(User $artist): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $artist->full_name,
            'url' => route('artists.show', $artist->slug ?? $artist->id),
            'image' => $artist->avatar ? asset('storage/' . $artist->avatar) : null,
            'description' => $artist->bio,
            'jobTitle' => 'Artist',
            'worksFor' => [
                '@type' => 'Organization',
                'name' => 'PanchiGallery',
            ],
            'sameAs' => array_filter([
                $artist->website,
                $artist->instagram ? 'https://instagram.com/' . $artist->instagram : null,
                $artist->facebook,
            ]),
        ];
    }

    /**
     * Generate collection schema
     */
    public static function collection($collection): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Collection',
            'name' => $collection->name,
            'description' => $collection->description,
            'url' => route('collections.show', $collection->slug),
        ];
    }

    /**
     * Generate blog post schema
     */
    public static function blogPost($blog): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $blog->title,
            'description' => $blog->excerpt ?? $blog->description,
            'image' => $blog->image ? asset('storage/' . $blog->image) : null,
            'author' => [
                '@type' => 'Organization',
                'name' => 'PanchiGallery',
            ],
            'datePublished' => $blog->published_at?->format('Y-m-d'),
            'dateModified' => $blog->updated_at->format('Y-m-d'),
            'url' => route('blog.show', $blog->slug),
        ];
    }

    /**
     * Generate breadcrumb schema
     */
    public static function breadcrumb(array $items): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [],
        ];

        foreach ($items as $index => $item) {
            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        }

        return $schema;
    }

    /**
     * Generate product schema for artwork
     */
    public static function product(Artwork $artwork): array
    {
        $artist = $artwork->artist;
        $imageUrl = $artwork->images[0] ?? null;

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $artwork->title,
            'description' => $artwork->description,
            'image' => $imageUrl ? asset('storage/' . $imageUrl) : null,
            'brand' => [
                '@type' => 'Person',
                'name' => $artist->full_name,
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $artwork->price,
                'priceCurrency' => $artwork->currency,
                'availability' => $artwork->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => route('artworks.show', $artwork->slug),
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'PanchiGallery',
                ],
            ],
            'url' => route('artworks.show', $artwork->slug),
        ];
    }

    /**
     * Generate FAQ schema
     */
    public static function faq(array $faqs): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [],
        ];

        foreach ($faqs as $faq) {
            $schema['mainEntity'][] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ];
        }

        return $schema;
    }

    /**
     * Generate local business schema
     */
    public static function localBusiness(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'ArtGallery',
            'name' => 'PanchiGallery',
            'image' => asset('images/logo.png'),
            'url' => config('app.url'),
            'telephone' => '+95-9-123456789',
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'MM',
                'addressRegion' => 'Yangon',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => '16.8661',
                'longitude' => '96.1951',
            ],
            'openingHoursSpecification' => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                ],
                'opens' => '09:00',
                'closes' => '18:00',
            ],
            'priceRange' => '$$',
        ];
    }

    /**
     * Parse height from dimensions string
     */
    protected static function parseHeight(?string $dimensions): ?string
    {
        if (!$dimensions) return null;
        
        // Try to extract height from dimensions like "24x36" or "24\" x 36\""
        if (preg_match('/(\d+)\s*[xX×]\s*(\d+)/', $dimensions, $matches)) {
            return $matches[2]; // Second number is height
        }
        
        return null;
    }

    /**
     * Parse width from dimensions string
     */
    protected static function parseWidth(?string $dimensions): ?string
    {
        if (!$dimensions) return null;
        
        // Try to extract width from dimensions like "24x36" or "24\" x 36\""
        if (preg_match('/(\d+)\s*[xX×]\s*(\d+)/', $dimensions, $matches)) {
            return $matches[1]; // First number is width
        }
        
        return null;
    }

    /**
     * Render schema as JSON-LD
     */
    public static function render(array $schema): string
    {
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
    }
}
