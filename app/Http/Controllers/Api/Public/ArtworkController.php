<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\ApiController;
use App\Models\Artwork;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArtworkController extends ApiController
{
    /**
     * Display a listing of approved artworks with search and filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Artwork::query();

        // Use Meilisearch if search query is provided
        if ($request->filled('search')) {
            $artworks = Artwork::search($request->search)
                ->when($request->filled('category'), function ($q) use ($request) {
                    return $q->where('category', $request->category);
                })
                ->paginate($request->get('per_page', 20));
        } else {
            // Standard Eloquent filtering
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->filled('medium')) {
                $query->where('medium', $request->medium);
            }

            if ($request->filled('min_price')) {
                $query->where('price_usd', '>=', $request->min_price);
            }

            if ($request->filled('max_price')) {
                $query->where('price_usd', '<=', $request->max_price);
            }

            $artworks = $query->latest()->paginate($request->get('per_page', 20));
        }

        return $this->success($artworks);
    }

    /**
     * Display the specified artwork.
     */
    public function show(string $slug): JsonResponse
    {
        $artwork = Artwork::with(['artist:id,name,bio,avatar', 'category:id,name'])
            ->where('slug', $slug)
            ->first();

        if (!$artwork) {
            return $this->notFound('Artwork not found');
        }

        // Track view
        $artwork->increment('views_count');

        return $this->success($artwork);
    }

    /**
     * Get similar artworks.
     */
    public function similar(string $slug): JsonResponse
    {
        $artwork = Artwork::where('slug', $slug)->first();

        if (!$artwork) {
            return $this->notFound('Artwork not found');
        }

        $similar = Artwork::where('category_id', $artwork->category_id)
            ->where('id', '!=', $artwork->id)
            ->approved()
            ->latest()
            ->take(4)
            ->get();

        return $this->success($similar);
    }

    /**
     * Get categories with counts.
     */
    public function categories(): JsonResponse
    {
        $categories = Category::withCount('artworks')->orderBy('name')->get();
        return $this->success($categories);
    }
}
