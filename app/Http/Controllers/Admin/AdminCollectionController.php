<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection as ArtCollection;
use App\Repositories\Interfaces\CollectionRepositoryInterface;
use Illuminate\Http\Request;

class AdminCollectionController extends Controller
{
    protected CollectionRepositoryInterface $collectionRepository;

    public function __construct(CollectionRepositoryInterface $collectionRepository)
    {
        $this->collectionRepository = $collectionRepository;
    }

    /**
     * Display all collections.
     */
    public function collections()
    {
        $collections = $this->collectionRepository->getWithArtworkCount();

        return view('admin.collections.index', compact('collections'));
    }

    /**
     * Show the form for creating a new collection.
     */
    public function createCollection()
    {
        $artworks = \App\Models\Artwork::approved()->get();

        return view('admin.collections.create', compact('artworks'));
    }

    /**
     * Store a newly created collection.
     */
    public function storeCollection(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:collections',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'artwork_ids' => 'nullable|array',
            'artwork_ids.*' => 'exists:artworks,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $collection = ArtCollection::create($validated);

        if ($request->filled('artwork_ids')) {
            $collection->artworks()->sync($validated['artwork_ids']);
        }

        return redirect()->route('admin.collections')
            ->with('success', 'Collection created successfully.');
    }

    /**
     * Show the form for editing collection.
     */
    public function editCollection(ArtCollection $collection)
    {
        $collection->load('artworks');
        $artworks = \App\Models\Artwork::approved()->get();

        return view('admin.collections.edit', compact('collection', 'artworks'));
    }

    /**
     * Update collection.
     */
    public function updateCollection(Request $request, ArtCollection $collection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:collections,name,' . $collection->id,
            'slug' => 'required|string|max:255|unique:collections,slug,' . $collection->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'artwork_ids' => 'nullable|array',
            'artwork_ids.*' => 'exists:artworks,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $collection->update($validated);

        if ($request->filled('artwork_ids')) {
            $collection->artworks()->sync($validated['artwork_ids']);
        }

        return redirect()->route('admin.collections')
            ->with('success', 'Collection updated successfully.');
    }

    /**
     * Delete collection.
     */
    public function deleteCollection(ArtCollection $collection)
    {
        $collection->artworks()->detach();
        $collection->delete();

        return redirect()->route('admin.collections')
            ->with('success', 'Collection deleted successfully.');
    }
}
