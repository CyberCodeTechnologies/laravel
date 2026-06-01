<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Exhibition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ExhibitionManagementController extends ApiController
{
    /**
     * Display a listing of exhibitions.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Exhibition::withTrashed();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_published')) {
            $query->where('is_published', $request->boolean('is_published'));
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        $exhibitions = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success($exhibitions);
    }

    /**
     * Store a newly created exhibition.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'venue' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'status' => ['nullable', 'in:upcoming,ongoing,completed,cancelled'],
            'is_featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('exhibitions/featured', 'public');
        }

        // Handle images upload
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('exhibitions/gallery', 'public');
            }
            $data['images'] = $images;
        }

        // Set default status based on dates
        if (empty($data['status'])) {
            $startDate = \Carbon\Carbon::parse($request->start_date);
            $endDate = \Carbon\Carbon::parse($request->end_date);
            $now = \Carbon\Carbon::now();

            if ($now->lt($startDate)) {
                $data['status'] = 'upcoming';
            } elseif ($now->between($startDate, $endDate)) {
                $data['status'] = 'ongoing';
            } else {
                $data['status'] = 'completed';
            }
        }

        $exhibition = Exhibition::create($data);

        return $this->success($exhibition, 'Exhibition created successfully', 201);
    }

    /**
     * Display the specified exhibition.
     */
    public function show(int $id): JsonResponse
    {
        $exhibition = Exhibition::withTrashed()->with(['artists', 'artworks'])->find($id);

        if (!$exhibition) {
            return $this->notFound('Exhibition not found');
        }

        return $this->success($exhibition);
    }

    /**
     * Update the specified exhibition.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $exhibition = Exhibition::withTrashed()->find($id);

        if (!$exhibition) {
            return $this->notFound('Exhibition not found');
        }

        $validator = Validator::make($request->all(), [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['sometimes', 'required', 'date', 'after:start_date'],
            'venue' => ['sometimes', 'required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'status' => ['sometimes', 'in:upcoming,ongoing,completed,cancelled'],
            'is_featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $data = $request->all();

        // Update slug if title changed
        if ($request->has('title') && $request->title !== $exhibition->title) {
            $data['slug'] = Str::slug($request->title);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            if ($exhibition->featured_image) {
                Storage::disk('public')->delete($exhibition->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('exhibitions/featured', 'public');
        }

        // Handle images upload
        if ($request->hasFile('images')) {
            // Delete old images
            if ($exhibition->images && is_array($exhibition->images)) {
                foreach ($exhibition->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('exhibitions/gallery', 'public');
            }
            $data['images'] = $images;
        }

        $exhibition->update($data);

        return $this->success($exhibition, 'Exhibition updated successfully');
    }

    /**
     * Remove the specified exhibition.
     */
    public function destroy(int $id): JsonResponse
    {
        $exhibition = Exhibition::withTrashed()->find($id);

        if (!$exhibition) {
            return $this->notFound('Exhibition not found');
        }

        // Delete images
        if ($exhibition->featured_image) {
            Storage::disk('public')->delete($exhibition->featured_image);
        }

        if ($exhibition->images && is_array($exhibition->images)) {
            foreach ($exhibition->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $exhibition->forceDelete();

        return $this->success(null, 'Exhibition deleted successfully');
    }

    /**
     * Restore a soft-deleted exhibition.
     */
    public function restore(int $id): JsonResponse
    {
        $exhibition = Exhibition::withTrashed()->find($id);

        if (!$exhibition) {
            return $this->notFound('Exhibition not found');
        }

        if (!$exhibition->trashed()) {
            return $this->error('Exhibition is not deleted', 400);
        }

        $exhibition->restore();

        return $this->success($exhibition, 'Exhibition restored successfully');
    }

    /**
     * Add artists to exhibition.
     */
    public function addArtists(Request $request, int $id): JsonResponse
    {
        $exhibition = Exhibition::find($id);

        if (!$exhibition) {
            return $this->notFound('Exhibition not found');
        }

        $validator = Validator::make($request->all(), [
            'artist_ids' => ['required', 'array'],
            'artist_ids.*' => ['exists:users,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $exhibition->artists()->syncWithoutDetaching($request->artist_ids);

        return $this->success($exhibition->load('artists'), 'Artists added successfully');
    }

    /**
     * Remove artists from exhibition.
     */
    public function removeArtists(Request $request, int $id): JsonResponse
    {
        $exhibition = Exhibition::find($id);

        if (!$exhibition) {
            return $this->notFound('Exhibition not found');
        }

        $validator = Validator::make($request->all(), [
            'artist_ids' => ['required', 'array'],
            'artist_ids.*' => ['exists:users,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $exhibition->artists()->detach($request->artist_ids);

        return $this->success($exhibition->load('artists'), 'Artists removed successfully');
    }

    /**
     * Add artworks to exhibition.
     */
    public function addArtworks(Request $request, int $id): JsonResponse
    {
        $exhibition = Exhibition::find($id);

        if (!$exhibition) {
            return $this->notFound('Exhibition not found');
        }

        $validator = Validator::make($request->all(), [
            'artwork_ids' => ['required', 'array'],
            'artwork_ids.*' => ['exists:artworks,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $syncData = [];
        foreach ($request->artwork_ids as $index => $artworkId) {
            $syncData[$artworkId] = ['order' => $index];
        }

        $exhibition->artworks()->syncWithoutDetaching($syncData);

        return $this->success($exhibition->load('artworks'), 'Artworks added successfully');
    }

    /**
     * Remove artworks from exhibition.
     */
    public function removeArtworks(Request $request, int $id): JsonResponse
    {
        $exhibition = Exhibition::find($id);

        if (!$exhibition) {
            return $this->notFound('Exhibition not found');
        }

        $validator = Validator::make($request->all(), [
            'artwork_ids' => ['required', 'array'],
            'artwork_ids.*' => ['exists:artworks,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $exhibition->artworks()->detach($request->artwork_ids);

        return $this->success($exhibition->load('artworks'), 'Artworks removed successfully');
    }

    /**
     * Update exhibition status.
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $exhibition = Exhibition::find($id);

        if (!$exhibition) {
            return $this->notFound('Exhibition not found');
        }

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:upcoming,ongoing,completed,cancelled'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $exhibition->update(['status' => $request->status]);

        return $this->success($exhibition, 'Exhibition status updated successfully');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(int $id): JsonResponse
    {
        $exhibition = Exhibition::find($id);

        if (!$exhibition) {
            return $this->notFound('Exhibition not found');
        }

        $exhibition->update(['is_featured' => !$exhibition->is_featured]);

        return $this->success($exhibition, 'Featured status toggled successfully');
    }

    /**
     * Toggle published status.
     */
    public function togglePublished(int $id): JsonResponse
    {
        $exhibition = Exhibition::find($id);

        if (!$exhibition) {
            return $this->notFound('Exhibition not found');
        }

        $exhibition->update(['is_published' => !$exhibition->is_published]);

        return $this->success($exhibition, 'Published status toggled successfully');
    }
}
