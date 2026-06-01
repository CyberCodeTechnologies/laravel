<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIDescriptionGeneratorService
{
    protected string $apiKey;
    protected string $apiEndpoint;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->apiEndpoint = config('services.openai.endpoint', 'https://api.openai.com/v1/chat/completions');
    }

    /**
     * Generate artwork description and related content
     */
    public function generate(array $artworkData): array
    {
        try {
            $prompt = $this->buildPrompt($artworkData);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiEndpoint, [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert art curator and SEO specialist for an art gallery in Myanmar. Generate professional, engaging content for artworks.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 1500,
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                return $this->parseResponse($content);
            }

            Log::error('AI Description Generation Failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return $this->getFallbackContent($artworkData);

        } catch (\Exception $e) {
            Log::error('AI Description Generation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->getFallbackContent($artworkData);
        }
    }

    /**
     * Build prompt for AI
     */
    protected function buildPrompt(array $data): string
    {
        $prompt = "Generate content for an artwork with the following details:\n\n";
        
        if (!empty($data['title'])) {
            $prompt .= "Title: {$data['title']}\n";
        }
        
        if (!empty($data['medium'])) {
            $prompt .= "Medium: {$data['medium']}\n";
        }
        
        if (!empty($data['dimensions'])) {
            $prompt .= "Dimensions: {$data['dimensions']}\n";
        }
        
        if (!empty($data['style'])) {
            $prompt .= "Style: {$data['style']}\n";
        }
        
        if (!empty($data['category'])) {
            $prompt .= "Category: {$data['category']}\n";
        }

        $prompt .= "\nPlease generate the following in JSON format:\n";
        $prompt .= "{\n";
        $prompt .= "  \"title\": \"Suggested title (if not provided)\",\n";
        $prompt .= "  \"description\": \"Professional artwork description (2-3 paragraphs)\",\n";
        $prompt .= "  \"seo_keywords\": [\"keyword1\", \"keyword2\", \"keyword3\", \"keyword4\", \"keyword5\"],\n";
        $prompt .= "  \"facebook_post\": \"Engaging Facebook post (1-2 paragraphs with emojis)\",\n";
        $prompt .= "  \"instagram_caption\": \"Instagram caption with hashtags (1-2 paragraphs)\",\n";
        $prompt .= "  \"short_description\": \"Brief description for cards (1-2 sentences)\"\n";
        $prompt .= "}\n\n";
        $prompt .= "Focus on Myanmar art context and SEO keywords for: Myanmar art, Myanmar painting, buy paintings Myanmar, art gallery Myanmar.";

        return $prompt;
    }

    /**
     * Parse AI response
     */
    protected function parseResponse(string $content): array
    {
        // Try to extract JSON from response
        $jsonStart = strpos($content, '{');
        $jsonEnd = strrpos($content, '}');
        
        if ($jsonStart !== false && $jsonEnd !== false) {
            $jsonString = substr($content, $jsonStart, $jsonEnd - $jsonStart + 1);
            $parsed = json_decode($jsonString, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                return [
                    'title' => $parsed['title'] ?? null,
                    'description' => $parsed['description'] ?? '',
                    'seo_keywords' => $parsed['seo_keywords'] ?? [],
                    'facebook_post' => $parsed['facebook_post'] ?? '',
                    'instagram_caption' => $parsed['instagram_caption'] ?? '',
                    'short_description' => $parsed['short_description'] ?? '',
                ];
            }
        }

        // Fallback: parse line by line
        return $this->parseLineByLine($content);
    }

    /**
     * Parse response line by line (fallback)
     */
    protected function parseLineByLine(string $content): array
    {
        $result = [
            'title' => null,
            'description' => '',
            'seo_keywords' => [],
            'facebook_post' => '',
            'instagram_caption' => '',
            'short_description' => '',
        ];

        $lines = explode("\n", $content);
        $currentSection = null;

        foreach ($lines as $line) {
            $line = trim($line);
            
            if (str_starts_with($line, 'Title:')) {
                $result['title'] = trim(substr($line, 6));
            } elseif (str_starts_with($line, 'Description:')) {
                $currentSection = 'description';
            } elseif (str_starts_with($line, 'SEO Keywords:')) {
                $currentSection = 'seo_keywords';
                $keywords = trim(substr($line, 13));
                $result['seo_keywords'] = array_map('trim', explode(',', $keywords));
            } elseif (str_starts_with($line, 'Facebook Post:')) {
                $currentSection = 'facebook_post';
            } elseif (str_starts_with($line, 'Instagram Caption:')) {
                $currentSection = 'instagram_caption';
            } elseif (str_starts_with($line, 'Short Description:')) {
                $currentSection = 'short_description';
            } elseif (!empty($line) && $currentSection) {
                $result[$currentSection] .= $line . ' ';
            }
        }

        return $result;
    }

    /**
     * Get fallback content when AI fails
     */
    protected function getFallbackContent(array $data): array
    {
        $title = $data['title'] ?? 'Untitled Artwork';
        $medium = $data['medium'] ?? 'Mixed Media';
        $style = $data['style'] ?? 'Contemporary';
        
        return [
            'title' => $title,
            'description' => "A beautiful {$style} artwork created in {$medium}. This piece showcases exceptional craftsmanship and artistic vision, perfect for collectors and art enthusiasts in Myanmar.",
            'seo_keywords' => [
                'Myanmar art',
                'Myanmar painting',
                'buy paintings Myanmar',
                'art gallery Myanmar',
                strtolower($medium),
                strtolower($style)
            ],
            'facebook_post' => "🎨 Discover this stunning {$style} artwork at PanchiGallery! A beautiful {$medium} piece that captures the essence of Myanmar's artistic heritage. Perfect for your collection. #MyanmarArt #ArtGallery #PanchiGallery",
            'instagram_caption' => "✨ New artwork alert! ✨\n\nThis beautiful {$style} piece in {$medium} is now available at PanchiGallery. A perfect addition to any art collection.\n\n📍 Myanmar Art Gallery\n🎨 {$medium}\n✨ {$style}\n\n#MyanmarArt #ArtGallery #PanchiGallery #ArtCollecting #MyanmarPainting",
            'short_description' => "A stunning {$style} artwork in {$medium}, perfect for collectors seeking authentic Myanmar art.",
        ];
    }

    /**
     * Generate title only
     */
    public function generateTitle(array $data): string
    {
        $result = $this->generate($data);
        return $result['title'] ?? $data['title'] ?? 'Untitled Artwork';
    }

    /**
     * Generate description only
     */
    public function generateDescription(array $data): string
    {
        $result = $this->generate($data);
        return $result['description'] ?? '';
    }

    /**
     * Generate SEO keywords only
     */
    public function generateSEOKeywords(array $data): array
    {
        $result = $this->generate($data);
        return $result['seo_keywords'] ?? [];
    }

    /**
     * Generate social media post
     */
    public function generateSocialPost(array $data, string $platform = 'facebook'): string
    {
        $result = $this->generate($data);
        
        return match($platform) {
            'facebook' => $result['facebook_post'] ?? '',
            'instagram' => $result['instagram_caption'] ?? '',
            default => $result['facebook_post'] ?? '',
        };
    }
}
