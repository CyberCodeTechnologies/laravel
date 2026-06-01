<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArtistController extends ApiController
{
    /**
     * Display a listing of approved artists.
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::where('role', 'artist')->where('is_approved', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $artists = $query->latest()->paginate($request->get('per_page', 20));

        return $this->success($artists);
    }

    /**
     * Display the specified artist and their artworks.
     */
    public function show(string $slug): JsonResponse
    {
        $artist = User::where('role', 'artist')
            ->where('slug', $slug)
            ->where('is_approved', true)
            ->first();

        if (!$artist) {
            return $this->notFound('Artist not found');
        }

        $artworks = $artist->artworks()->approved()->latest()->paginate(12);

        return $this->success([
            'artist' => $artist,
            'artworks' => $artworks
        ]);
    }
}
