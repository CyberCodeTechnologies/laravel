<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CollectionManagementController extends ApiController
{
    /**
     * Display a listing of collections.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Collection::with(['curator:id,name', 'artworks']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('curator_id')) {
            $query->where('curator_id', $request->curator_id);
        }

        $collections = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success($collections);
    }

    /**
     * Store a newly created collection.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'curator_id' => ['nullable', 'exists:users,id'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('collections/featured', 'public');
        }

        $collection = Collection::create($data);

        return $this->success($collection, 'Collection created successfully', 201);
    }

    /**
     * Display the specified collection.
     */
    public function show(int $id): JsonResponse
    {
        $collection = Collection::with(['curator:id,name,email', 'artworks.artist'])->find($id);

        if (!$collection) {
            return $this->notFound('Collection not found');
        }

        return $this->success($collection);
    }

    /**
     * Update the specified collection.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $collection = Collection::find($id);

        if (!$collection) {
            return $this->notFound('Collection not found');
        }

        $validator = Validator::make($request->all(), [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'curator_id' => ['nullable', 'exists:users,id'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $data = $request->all();

        // Update slug if title changed
        if ($request->has('title') && $request->title !== $collection->title) {
            $data['slug'] = Str::slug($request->title);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            if ($collection->featured_image) {
                Storage::disk('public')->delete($collection->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('collections/featured', 'public');
        }

        $collection->update($data);

        return $this->success($collection, 'Collection updated successfully');
    }

    /**
     * Remove the specified collection.
     */
    public function destroy(int $id): JsonResponse
    {
        $collection = Collection::find($id);

        if (!$collection) {
            return $this->notFound('Collection not found');
        }

        // Delete featured image
        if ($collection->featured_image) {
            Storage::disk('public')->delete($collection->featured_image);
        }

        $collection->delete();

        return $this->success(null, 'Collection deleted successfully');
    }

    /**
     * Add artworks to collection.
     */
    public function addArtworks(Request $request, int $id): JsonResponse
    {
        $collection = Collection::find($id);

        if (!$collection) {
            return $this->notFound('Collection not found');
        }

        $validator = Validator::make($request->all(), [
            'artwork_ids' => ['required', 'array'],
            'artwork_ids.*' => ['exists:artworks,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $collection->artworks()->syncWithoutDetaching($request->artwork_ids);

        return $this->success($collection->load('artworks'), 'Artworks added successfully');
    }

    /**
     * Remove artworks from collection.
     */
    public function removeArtworks(Request $request, int $id): JsonResponse
    {
        $collection = Collection::find($id);

        if (!$collection) {
            return $this->notFound('Collection not found');
        }

        $validator = Validator::make($request->all(), [
            'artwork_ids' => ['required', 'array'],
            'artwork_ids.*' => ['exists:artworks,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $collection->artworks()->detach($request->artwork_ids);

        return $this->success($collection->load('artworks'), 'Artworks removed successfully');
    }

    /**
     * Update collection order.
     */
    public function updateOrder(Request $request, int $id): JsonResponse
    {
        $collection = Collection::find($id);

        if (!$collection) {
            return $this->notFound('Collection not found');
        }

        $validator = Validator::make($request->all(), [
            'artwork_orders' => ['required', 'array'],
            'artwork_orders.*.artwork_id' => ['required', 'exists:artworks,id'],
            'artwork_orders.*.order' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $syncData = [];
        foreach ($request->artwork_orders as $item) {
            $syncData[$item['artwork_id']] = ['order' => $item['order']];
        }

        $collection->artworks()->sync($syncData);

        return $this->success($collection->load('artworks'), 'Collection order updated successfully');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(int $id): JsonResponse
    {
        $collection = Collection::find($id);

        if (!$collection) {
            return $this->notFound('Collection not found');
        }

        $collection->update(['is_featured' => !$collection->is_featured]);

        return $this->success($collection, 'Featured status toggled successfully');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(int $id): JsonResponse
    {
        $collection = Collection::find($id);

        if (!$collection) {
            return $this->notFound('Collection not found');
        }

        $collection->update(['is_active' => !$collection->is_active]);

        return $this->success($collection, 'Active status toggled successfully');
    }
}
