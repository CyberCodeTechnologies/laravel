<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\Resale;
use App\Models\Scopes\ApprovedScope;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArtworkModerationController extends ApiController
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Display a listing of artworks for moderation.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Artwork::withoutGlobalScope(ApprovedScope::class)->with(['artist:id,name', 'category:id,name']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $artworks = $query->latest()->paginate($request->get('per_page', 20));

        return $this->success($artworks);
    }

    /**
     * Store a newly created artwork.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'artist_id' => ['required', 'exists:users,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'medium' => ['required', 'string'],
            'dimensions' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'in:USD,MMK'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'images' => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'status' => ['nullable', 'in:pending,approved,sold'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $images = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('artworks', 'public');
            $images[] = $path;
        }

        $price = $request->price;
        $currency = $request->currency;

        $artwork = Artwork::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'description' => $request->description,
            'artist_id' => $request->artist_id,
            'category_id' => $request->category_id,
            'medium' => $request->medium,
            'dimensions' => $request->dimensions,
            'price' => $price,
            'currency' => $currency,
            'year' => $request->year,
            'images' => $images,
            'status' => $request->status ?? 'pending',
            'price_usd' => $currency === 'USD' ? $price : $this->currencyService->convert($price, $currency, 'USD'),
            'price_mmk' => $currency === 'MMK' ? $price : $this->currencyService->convert($price, $currency, 'MMK'),
            'stock' => 1,
            'is_approved' => $request->status === 'approved',
            'approved_at' => $request->status === 'approved' ? now() : null,
        ]);

        return $this->success($artwork, 'Artwork created successfully', 201);
    }

    /**
     * Update the specified artwork.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $artwork = Artwork::withoutGlobalScope(ApprovedScope::class)->find($id);

        if (!$artwork) {
            return $this->notFound('Artwork not found');
        }

        $validator = Validator::make($request->all(), [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'required', 'in:pending,approved,rejected,sold'],
            // Add other fields as needed
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $data = $request->all();

        if ($request->has('title') && $request->title !== $artwork->title) {
            $data['slug'] = Str::slug($request->title) . '-' . uniqid();
        }

        if ($request->has('price') || $request->has('currency')) {
            $price = $request->get('price', $artwork->price);
            $currency = $request->get('currency', $artwork->currency);
            $data['price_usd'] = $currency === 'USD' ? $price : $this->currencyService->convert($price, $currency, 'USD');
            $data['price_mmk'] = $currency === 'MMK' ? $price : $this->currencyService->convert($price, $currency, 'MMK');
        }

        if ($request->has('status') && $request->status === 'approved' && $artwork->status !== 'approved') {
            $data['approved_at'] = now();
            $data['is_approved'] = true;
        }

        $artwork->update($data);

        return $this->success($artwork, 'Artwork updated successfully');
    }

    /**
     * Approve an artwork.
     */
    public function approve(int $id): JsonResponse
    {
        $artwork = Artwork::withoutGlobalScope(ApprovedScope::class)->find($id);

        if (!$artwork) {
            return $this->notFound('Artwork not found');
        }

        $artwork->update([
            'status' => 'approved',
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        return $this->success($artwork, 'Artwork approved successfully');
    }

    /**
     * Reject an artwork.
     */
    public function reject(Request $request, int $id): JsonResponse
    {
        $artwork = Artwork::withoutGlobalScope(ApprovedScope::class)->find($id);

        if (!$artwork) {
            return $this->notFound('Artwork not found');
        }

        $validator = Validator::make($request->all(), [
            'reason' => ['required', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $artwork->update([
            'status' => 'rejected',
            'is_approved' => false,
            'rejection_reason' => $request->reason,
        ]);

        return $this->success($artwork, 'Artwork rejected successfully');
    }

    /**
     * Remove the specified artwork.
     */
    public function destroy(int $id): JsonResponse
    {
        $artwork = Artwork::withoutGlobalScope(ApprovedScope::class)->find($id);

        if (!$artwork) {
            return $this->notFound('Artwork not found');
        }

        if ($artwork->images && is_array($artwork->images)) {
            foreach ($artwork->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $artwork->delete();

        return $this->success(null, 'Artwork deleted successfully');
    }

    /**
     * Marketplace (Resale) moderation
     */
    public function resales(Request $request): JsonResponse
    {
        $query = Resale::with(['artwork.artist', 'owner']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $resales = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success($resales);
    }

    /**
     * Approve a resale listing.
     */
    public function approveResale(int $id): JsonResponse
    {
        $resale = Resale::find($id);

        if (!$resale) {
            return $this->notFound('Resale listing not found');
        }

        $resale->update([
            'status' => 'listed',
            'listed_at' => now(),
        ]);

        return $this->success($resale, 'Resale listing approved successfully');
    }
}
