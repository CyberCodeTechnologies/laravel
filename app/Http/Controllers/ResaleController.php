<?php

namespace App\Http\Controllers;

use App\Models\Resale;
use App\Models\Artwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResaleController extends Controller
{
    /**
     * Display a listing of resale artworks.
     */
    public function index(Request $request)
    {
        $query = Resale::with(['artwork.artist', 'artwork.category', 'owner'])
            ->listed()
            ->verified();
        
        // Apply filters
        if ($request->filled('category')) {
            $query->whereHas('artwork', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }
        
        if ($request->filled('medium')) {
            $query->whereHas('artwork', function ($q) use ($request) {
                $q->where('medium', $request->medium);
            });
        }
        
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->byPriceRange($request->min_price, $request->max_price);
        }
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('artwork', function ($subQ) use ($request) {
                    $subQ->where('title', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                })
                ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Apply ordering
        $sort = $request->get('sort', 'listed_at');
        $order = $request->get('order', 'desc');
        
        switch ($sort) {
            case 'price_low':
                $query->byPriceAsc();
                break;
            case 'price_high':
                $query->byPriceDesc();
                break;
            case 'newest':
                $query->newest();
                break;
            default:
                $query->orderBy($sort, $order);
        }
        
        $resales = $query->paginate(30);
        
        return view('marketplace.index', compact('resales'));
    }

    /**
     * Show the form for creating a new resale listing.
     */
    public function create(Artwork $artwork)
    {
        $this->authorize('resale', $artwork);
        
        // Verify ownership
        if (!$artwork->current_owner || $artwork->current_owner->id !== auth()->id()) {
            abort(403, 'You are not the current owner of this artwork.');
        }
        
        // Check if artwork is available for resale
        if (!$artwork->isAvailableForResale()) {
            return back()->with('error', 'This artwork is not available for resale.');
        }
        
        return view('resales.create', compact('artwork'));
    }

    /**
     * Store a newly created resale listing in storage.
     */
    public function store(Request $request, Artwork $artwork)
    {
        $this->authorize('resale', $artwork);
        
        $request->validate([
            'asking_price' => ['required', 'numeric', 'min:0'],
            'minimum_price' => ['nullable', 'numeric', 'min:0', 'lt:asking_price'],
            'description' => ['nullable', 'string', 'max:1000'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ]);

        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Validate file type and size
                if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    return back()->with('error', 'Invalid file type. Only jpg, jpeg, png, gif, and webp are allowed.');
                }
                if ($image->getSize() > 5 * 1024 * 1024) { // 5MB limit
                    return back()->with('error', 'File size too large. Maximum size is 5MB.');
                }
                $path = $image->store('resales', 'public');
                $images[] = $path;
            }
        }

        // Calculate currency conversions
        $currencyService = app(\App\Services\CurrencyService::class);
        $basePrice = $request->asking_price;
        $currency = $request->currency ?? 'USD';
        
        $resale = Resale::create([
            'artwork_id' => $artwork->id,
            'owner_id' => auth()->id(),
            'price' => $basePrice,
            'currency' => $currency,
            'price_usd' => $currency === 'USD' ? $basePrice : $currencyService->convert($basePrice, $currency, 'USD'),
            'price_mmk' => $currency === 'MMK' ? $basePrice : $currencyService->convert($basePrice, $currency, 'MMK'),
            'exchange_rate' => $currencyService->getRate($currency, 'USD'),
            'minimum_price' => $request->minimum_price,
            'description' => $request->description,
            'images' => $images,
            'status' => 'pending',
            'is_verified' => false,
        ]);
        
        return redirect()->route('resales.show', $resale)
            ->with('success', 'Resale listing submitted for approval. It will be reviewed within 24-48 hours.');
    }

    /**
     * Display the specified resale listing.
     */
    public function show($slug)
    {
        $artwork = Artwork::where('slug', $slug)->firstOrFail();
        $resale = Resale::with(['artwork.artist', 'artwork.category', 'owner'])
            ->where('artwork_id', $artwork->id)
            ->listed()
            ->verified()
            ->firstOrFail();

        if ($resale->status !== 'listed') {
            abort(404);
        }

        // Get similar resales
        $similarResales = Resale::with(['artwork.artist'])
            ->listed()
            ->verified()
            ->where('id', '!=', $resale->id)
            ->whereHas('artwork', function ($q) use ($resale) {
                $q->where('category_id', $resale->artwork->category_id);
            })
            ->take(6)
            ->get();

        // Generate QR code URL for marketplace page
        $marketplaceUrl = route('marketplace.show', $artwork->slug);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&margin=2&data=' . urlencode($marketplaceUrl);

        return view('marketplace.show', compact('resale', 'similarResales', 'qrCodeUrl'));
    }

    /**
     * Show the form for editing the specified resale listing.
     */
    public function edit(Resale $resale)
    {
        $this->authorize('update', $resale);
        
        if ($resale->status === 'sold') {
            return back()->with('error', 'Cannot edit sold resale listing.');
        }
        
        return view('resales.edit', compact('resale'));
    }

    /**
     * Update the specified resale listing in storage.
     */
    public function update(Request $request, Resale $resale)
    {
        $this->authorize('update', $resale);
        
        if ($resale->status === 'sold') {
            return back()->with('error', 'Cannot edit sold resale listing.');
        }
        
        $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'in:USD,MMK'],
            'minimum_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'description' => ['nullable', 'string', 'max:1000'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ]);
        
        $data = $request->except('images');
        
        // Calculate currency conversions
        $currencyService = app(\App\Services\CurrencyService::class);
        $basePrice = $request->price;
        $currency = $request->currency;
        
        $data['price'] = $basePrice;
        $data['currency'] = $currency;
        $data['price_usd'] = $currency === 'USD' ? $basePrice : $currencyService->convert($basePrice, $currency, 'USD');
        $data['price_mmk'] = $currency === 'MMK' ? $basePrice : $currencyService->convert($basePrice, $currency, 'MMK');
        $data['exchange_rate'] = $currencyService->getRate($currency, 'USD');
        
        // Handle new image uploads
        if ($request->hasFile('images')) {
            // Delete old images
            if ($resale->images) {
                foreach ($resale->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            
            // Upload new images
            $images = [];
            foreach ($request->file('images') as $image) {
                // Validate file type and size
                if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    return back()->with('error', 'Invalid file type. Only jpg, jpeg, png, gif, and webp are allowed.');
                }
                if ($image->getSize() > 5 * 1024 * 1024) { // 5MB limit
                    return back()->with('error', 'File size too large. Maximum size is 5MB.');
                }
                $path = $image->store('resales', 'public');
                $images[] = $path;
            }
            $data['images'] = $images;
        }
        
        // Reset to pending if price changed significantly
        if (abs($resale->price - $request->price) > ($resale->price * 0.1)) {
            $data['status'] = 'pending';
            $data['is_verified'] = false;
        }
        
        $resale->update($data);
        
        return redirect()->route('resales.show', $resale)
            ->with('success', 'Resale listing updated successfully.');
    }

    /**
     * Remove the specified resale listing from storage.
     */
    public function destroy(Resale $resale)
    {
        $this->authorize('delete', $resale);
        
        // Delete images
        if ($resale->images) {
            foreach ($resale->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        
        $resale->delete();
        
        return redirect()->route('collector.resales')
            ->with('success', 'Resale listing deleted successfully.');
    }

    /**
     * Display resale listings for authenticated collector.
     */
    public function myResales()
    {
        $resales = auth()->user()->resales()
            ->with(['artwork.artist', 'artwork.category'])
            ->latest()
            ->paginate(12);
        
        return view('resales.my-resales', compact('resales'));
    }

    /**
     * Purchase a resale artwork.
     */
    public function purchase(Request $request, Resale $resale)
    {
        if (!$resale->isListed()) {
            return back()->with('error', 'This resale listing is not available.');
        }
        
        // Calculate fees
        $platformFee = Transaction::calculatePlatformFee($resale->price);
        $sellerEarnings = Transaction::calculateSellerEarnings($resale->price, $platformFee);
        
        return view('resales.purchase', compact('resale', 'platformFee', 'sellerEarnings'));
    }

    /**
     * Process resale purchase.
     */
    public function processPurchase(Request $request, Resale $resale)
    {
        if (!$resale->isListed()) {
            return back()->with('error', 'This resale listing is not available.');
        }
        
        $request->validate([
            'payment_method' => ['required', 'in:stripe,paypal,bank_transfer'],
            'buyer_notes' => ['nullable', 'string', 'max:500'],
        ]);
        
        $buyer = auth()->user();
        $seller = $resale->owner;
        
        if ($buyer->id === $seller->id) {
            return back()->with('error', 'Cannot purchase your own resale listing.');
        }
        
        // Calculate fees
        $platformFee = Transaction::calculatePlatformFee($resale->price);
        $sellerEarnings = Transaction::calculateSellerEarnings($resale->price, $platformFee);
        
        // Create transaction
        $transaction = Transaction::create([
            'artwork_id' => $resale->artwork_id,
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'transaction_id' => Transaction::generateTransactionId(),
            'amount' => $resale->price,
            'currency' => $resale->currency,
            'platform_fee' => $platformFee,
            'seller_earnings' => $sellerEarnings,
            'type' => 'resale',
            'status' => 'pending',
            'payment_method' => $request->payment_method,
        ]);
        
        // Process payment (this would integrate with payment gateways)
        $paymentResult = $this->processPayment($transaction, $request);
        
        if ($paymentResult['success']) {
            $transaction->update([
                'status' => 'completed',
                'payment_id' => $paymentResult['payment_id'],
                'completed_at' => now(),
            ]);
            
            // Complete transaction
            $transaction->complete();
            
            // Mark resale as sold
            $resale->markAsSold();
            
            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Resale purchase completed successfully!');
        } else {
            $transaction->update(['status' => 'failed']);
            return back()->with('error', 'Payment failed: ' . $paymentResult['message']);
        }
    }

    /**
     * Process payment for resale transaction.
     */
    private function processPayment(Transaction $transaction, Request $request)
    {
        // This would integrate with actual payment gateways
        try {
            switch ($request->payment_method) {
                case 'stripe':
                    return [
                        'success' => true,
                        'payment_id' => 'stripe_' . uniqid(),
                    ];
                case 'paypal':
                    return [
                        'success' => true,
                        'payment_id' => 'paypal_' . uniqid(),
                    ];
                case 'bank_transfer':
                    return [
                        'success' => true,
                        'payment_id' => 'bank_' . uniqid(),
                    ];
                default:
                    return [
                        'success' => false,
                        'message' => 'Unsupported payment method.',
                    ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Approve a resale listing (admin only).
     */
    public function approve(Resale $resale)
    {
        $this->authorize('approve', $resale);
        
        if (!$resale->verifyOwnership()) {
            return back()->with('error', 'Cannot approve resale listing: Ownership verification failed.');
        }
        
        $resale->approve();
        
        return back()->with('success', 'Resale listing approved successfully.');
    }

    /**
     * Reject a resale listing (admin only).
     */
    public function reject(Request $request, Resale $resale)
    {
        $this->authorize('approve', $resale);
        
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);
        
        $resale->reject($request->reason);
        
        return back()->with('success', 'Resale listing rejected.');
    }
}
