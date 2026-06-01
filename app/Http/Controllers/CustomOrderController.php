<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Artwork;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;

class CustomOrderController extends Controller
{
    /**
     * Display the professional order submission form.
     */
    public function create()
    {
        // Get approved artists with their artwork count who want to participate in orders
        $artists = User::where('role', 'artist')
            ->where('is_approved', true)
            ->where('participate_in_orders', true)
            ->withCount(['artworks' => function($query) {
                $query->where('status', 'approved');
            }])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        // Enhanced medium options with descriptions
        $mediums = [
            'oil_painting' => [
                'name' => 'Oil Painting',
                'description' => 'Traditional oil on canvas, known for rich colors and depth',
                'estimated_time' => '2-4 weeks'
            ],
            'acrylic_painting' => [
                'name' => 'Acrylic Painting',
                'description' => 'Fast-drying acrylic on canvas, vibrant and versatile',
                'estimated_time' => '1-3 weeks'
            ],
            'watercolor' => [
                'name' => 'Watercolor',
                'description' => 'Delicate water-based painting on paper',
                'estimated_time' => '1-2 weeks'
            ],
            'digital_art' => [
                'name' => 'Digital Art',
                'description' => 'Digital illustration or painting, high-resolution printable',
                'estimated_time' => '1-2 weeks'
            ],
            'pencil_drawing' => [
                'name' => 'Pencil Drawing',
                'description' => 'Detailed graphite or colored pencil artwork',
                'estimated_time' => '1-2 weeks'
            ],
            'charcoal_drawing' => [
                'name' => 'Charcoal Drawing',
                'description' => 'Bold charcoal drawings with dramatic contrasts',
                'estimated_time' => '1-2 weeks'
            ],
            'mixed_media' => [
                'name' => 'Mixed Media',
                'description' => 'Combination of various artistic mediums',
                'estimated_time' => '2-4 weeks'
            ],
            'sculpture' => [
                'name' => 'Sculpture',
                'description' => '3D artwork in various materials',
                'estimated_time' => '3-6 weeks'
            ],
            'photography' => [
                'name' => 'Photography',
                'description' => 'Professional photography services',
                'estimated_time' => '1-2 weeks'
            ],
            'other' => [
                'name' => 'Other',
                'description' => 'Custom medium as discussed with artist',
                'estimated_time' => 'Varies'
            ],
        ];

        // Enhanced style options with descriptions
        $styles = [
            'realism' => [
                'name' => 'Realism',
                'description' => 'Highly detailed, realistic representation'
            ],
            'abstract' => [
                'name' => 'Abstract',
                'description' => 'Non-representational, expressive forms and colors'
            ],
            'impressionism' => [
                'name' => 'Impressionism',
                'description' => 'Light and color-focused, visible brush strokes'
            ],
            'modern' => [
                'name' => 'Modern',
                'description' => 'Contemporary artistic expression'
            ],
            'contemporary' => [
                'name' => 'Contemporary',
                'description' => 'Current artistic trends and styles'
            ],
            'traditional' => [
                'name' => 'Traditional',
                'description' => 'Classic, time-honored artistic techniques'
            ],
            'minimalist' => [
                'name' => 'Minimalist',
                'description' => 'Simple, clean, reduced to essential elements'
            ],
            'pop_art' => [
                'name' => 'Pop Art',
                'description' => 'Bold, colorful, popular culture inspired'
            ],
            'surrealism' => [
                'name' => 'Surrealism',
                'description' => 'Dream-like, imaginative, unexpected juxtapositions'
            ],
            'other' => [
                'name' => 'Other',
                'description' => 'Custom style as discussed with artist'
            ],
        ];

        // Size presets with pricing estimates
        $sizePresets = [
            'small' => ['name' => 'Small (8x10 inches)', 'price_multiplier' => 1.0],
            'medium' => ['name' => 'Medium (16x20 inches)', 'price_multiplier' => 1.5],
            'large' => ['name' => 'Large (24x30 inches)', 'price_multiplier' => 2.0],
            'extra_large' => ['name' => 'Extra Large (36x48 inches)', 'price_multiplier' => 3.0],
            'custom' => ['name' => 'Custom Size', 'price_multiplier' => 1.0],
        ];

        // Get similar orders for reference
        $similarOrders = Order::where('order_type', 'custom')
            ->where('custom_status', 'completed')
            ->with(['user', 'artist'])
            ->latest()
            ->take(5)
            ->get();

        return view('orders.create', compact(
            'artists', 
            'mediums', 
            'styles', 
            'sizePresets',
            'similarOrders'
        ));
    }

    /**
     * Store a new custom artwork order with enhanced validation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'artist_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255|min:3',
            'description' => 'required|string|max:2000|min:20',
            'artwork_shape' => 'required|in:rectangle,square,circle,oval,triangle,custom',
            'rect_width' => 'required_if:artwork_shape,rectangle|integer|min:4|max:96',
            'rect_height' => 'required_if:artwork_shape,rectangle|integer|min:4|max:96',
            'square_size' => 'required_if:artwork_shape,square|integer|min:4|max:96',
            'circle_diameter' => 'required_if:artwork_shape,circle|integer|min:4|max:96',
            'oval_width' => 'required_if:artwork_shape,oval|integer|min:4|max:96',
            'oval_height' => 'required_if:artwork_shape,oval|integer|min:4|max:96',
            'triangle_base' => 'required_if:artwork_shape,triangle|integer|min:4|max:96',
            'triangle_height' => 'required_if:artwork_shape,triangle|integer|min:4|max:96',
            'custom_description' => 'required_if:artwork_shape,custom|string|max:500',
            'custom_max_width' => 'required_if:artwork_shape,custom|integer|min:4|max:96',
            'custom_max_height' => 'required_if:artwork_shape,custom|integer|min:4|max:96',
            'medium' => 'required|string|max:100',
            'style' => 'required|string|max:100',
            'proposed_price' => 'required|numeric|min:200|max:10000',
            'reference_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'customer_notes' => 'nullable|string|max:1000',
            'deadline' => 'nullable|date|after:today',
            'budget_flexibility' => 'nullable|in:fixed,flexible,negotiable',
            'shipping_requirements' => 'nullable|string|max:500',
        ]);

        // Generate unique order number
        $orderNumber = 'ORD-' . date('Y') . '-' . strtoupper(Str::random(6));

        // Calculate final price based on shape and dimensions
        $basePrice = $request->proposed_price;
        $sizeMultiplier = $this->calculateSizeMultiplier($request);
        $finalPrice = $basePrice * $sizeMultiplier;

        $orderData = [
            'order_number' => $orderNumber,
            'user_id' => Auth::id(),
            'artist_id' => $request->artist_id,
            'title' => $request->title,
            'description' => $request->description,
            'size' => $this->formatSize($request),
            'medium' => $request->medium,
            'style' => $request->style,
            'proposed_price' => $request->proposed_price,
            'final_price' => $finalPrice,
            'customer_notes' => $request->customer_notes,
            'deadline' => $request->deadline,
            'budget_flexibility' => $request->budget_flexibility ?? 'fixed',
            'shipping_requirements' => $request->shipping_requirements,
            'order_type' => 'custom',
            'custom_status' => 'pending_artist_approval',
            'total_amount' => $finalPrice,
            'currency' => 'USD',
        ];

        // Handle multiple reference image uploads
        $referenceImages = [];
        if ($request->hasFile('reference_images')) {
            foreach ($request->file('reference_images') as $image) {
                $path = $image->store('order-references/' . $orderNumber, 'public');
                $referenceImages[] = $path;
            }
            $orderData['reference_images'] = json_encode($referenceImages);
        }
        
        // Add shape and dimension information
        $orderData['artwork_shape'] = $request->artwork_shape;
        $orderData['dimensions'] = $this->formatDimensions($request);

        // Create order within a transaction
        DB::beginTransaction();
        try {
            $order = Order::create($orderData);
            
            // Send notification to artist
            $artist = User::find($request->artist_id);
            \Mail::to($artist->email)->send(new \App\Mail\ContactArtistMail([
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'subject' => 'New Custom Order Request - #' . $orderNumber,
                'message' => 'You have received a new custom artwork order request. Please review the details in your dashboard.'
            ]));
            
            // Send confirmation to customer
            \Mail::to(auth()->user()->email)->send(new \App\Mail\OrderConfirmationMail($order));
            
            DB::commit();
            
            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Your custom artwork order #' . $orderNumber . ' has been submitted successfully! The artist will review your request within 48 hours.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded files if order creation fails
            if (!empty($referenceImages)) {
                foreach ($referenceImages as $imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
            
            return back()
                ->withInput()
                ->with('error', 'There was an issue submitting your order. Please try again or contact support.');
        }
    }

    /**
     * Calculate size multiplier based on shape and dimensions
     */
    private function calculateSizeMultiplier($request)
    {
        $area = $this->calculateArea($request);
        
        if ($area < 100) return 1.0;
        if ($area < 200) return 1.2;
        if ($area < 400) return 1.5;
        if ($area < 800) return 2.0;
        if ($area < 1500) return 2.5;
        return 3.0;
    }
    
    /**
     * Calculate area based on shape and dimensions
     */
    private function calculateArea($request)
    {
        switch ($request->artwork_shape) {
            case 'rectangle':
                return $request->rect_width * $request->rect_height;
                
            case 'square':
                return $request->square_size * $request->square_size;
                
            case 'circle':
                $radius = $request->circle_diameter / 2;
                return pi() * $radius * $radius;
                
            case 'oval':
                $widthRadius = $request->oval_width / 2;
                $heightRadius = $request->oval_height / 2;
                return pi() * $widthRadius * $heightRadius;
                
            case 'triangle':
                return 0.5 * $request->triangle_base * $request->triangle_height;
                
            case 'custom':
                // Estimate 70% of bounding box area for custom shapes
                return $request->custom_max_width * $request->custom_max_height * 0.7;
                
            default:
                return 100; // Default area
        }
    }
    
    /**
     * Format dimensions for display
     */
    private function formatDimensions($request)
    {
        switch ($request->artwork_shape) {
            case 'rectangle':
                return $request->rect_width . '" × ' . $request->rect_height . '"';
                
            case 'square':
                return $request->square_size . '" × ' . $request->square_size . '"';
                
            case 'circle':
                return 'Ø ' . $request->circle_diameter . '"';
                
            case 'oval':
                return $request->oval_width . '" × ' . $request->oval_height . '"';
                
            case 'triangle':
                return 'Base: ' . $request->triangle_base . '" × Height: ' . $request->triangle_height . '"';
                
            case 'custom':
                return $request->custom_description . ' (Max: ' . $request->custom_max_width . '" × ' . $request->custom_max_height . '"';
                
            default:
                return 'Custom dimensions';
        }
    }

    /**
     * Format size string for display (legacy method)
     */
    private function formatSize($request)
    {
        return $this->formatDimensions($request);
    }

    /**
     * Display order details.
     */
    public function show(Order $order)
    {
        // Check if user can view this order
        if (Auth::id() !== $order->user_id && Auth::id() !== $order->artist_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Display customer's orders.
     */
    public function customerOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->custom()
            ->with('artist')
            ->latest()
            ->paginate(10);

        return view('orders.customer.index', compact('orders'));
    }

    /**
     * Display artist's custom orders.
     */
    public function artistOrders()
    {
        $orders = Order::where('artist_id', Auth::id())
            ->custom()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('orders.artist.index', compact('orders'));
    }

    /**
     * Accept a custom order (Artist action).
     */
    public function acceptOrder(Order $order)
    {
        // Check if current user is the assigned artist
        if (Auth::id() !== $order->artist_id || !$order->canArtistAccept()) {
            abort(403);
        }

        $order->updateCustomStatus('artist_accepted');

        return redirect()
            ->route('artist.orders')
            ->with('success', __('messages.artist_dashboard.order_accepted_success'));
    }

    /**
     * Reject a custom order (Artist action).
     */
    public function rejectOrder(Request $request, Order $order)
    {
        // Check if current user is the assigned artist
        if (Auth::id() !== $order->artist_id || !$order->canArtistReject()) {
            abort(403);
        }

        $request->validate([
            'artist_notes' => 'required|string|max:1000',
        ]);

        $order->update([
            'custom_status' => 'artist_rejected',
            'artist_notes' => $request->artist_notes,
        ]);

        return redirect()
            ->route('artist.orders')
            ->with('success', __('messages.artist_dashboard.order_rejected_success'));
    }

    /**
     * Mark order as in progress (Artist action).
     */
    public function startProgress(Order $order)
    {
        // Check if current user is the assigned artist
        if (Auth::id() !== $order->artist_id || !$order->canStartProgress()) {
            abort(403);
        }

        $order->updateCustomStatus('in_progress');

        return redirect()
            ->route('artist.orders')
            ->with('success', __('messages.artist_dashboard.order_started_success'));
    }

    /**
     * Mark order as ready for review (Artist action).
     */
    public function markReadyForReview(Order $order)
    {
        // Check if current user is the assigned artist
        if (Auth::id() !== $order->artist_id || !$order->canMarkReadyForReview()) {
            abort(403);
        }

        $order->updateCustomStatus('ready_for_review');

        return redirect()
            ->route('artist.orders')
            ->with('success', __('messages.artist_dashboard.order_ready_success'));
    }

    /**
     * Approve completed artwork (Customer action).
     */
    public function customerApprove(Order $order)
    {
        // Check if current user is the customer
        if (Auth::id() !== $order->user_id || !$order->canCustomerApprove()) {
            abort(403);
        }

        $order->updateCustomStatus('customer_approved');

        return redirect()
            ->route('orders.customer.index')
            ->with('success', 'Artwork approved! The artist will proceed with shipping.');
    }

    /**
     * Reject completed artwork (Customer action).
     */
    public function customerReject(Request $request, Order $order)
    {
        // Check if current user is the customer
        if (Auth::id() !== $order->user_id || !$order->canCustomerReject()) {
            abort(403);
        }

        $request->validate([
            'customer_notes' => 'required|string|max:1000',
        ]);

        $order->update([
            'custom_status' => 'customer_rejected',
            'customer_notes' => $request->customer_notes,
        ]);

        return redirect()
            ->route('orders.customer.index')
            ->with('success', 'Feedback sent to the artist for revisions.');
    }

    /**
     * Display order tracking page.
     */
    public function track(Order $order)
    {
        // Check if user can track this order
        if (Auth::id() !== $order->user_id && Auth::id() !== $order->artist_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('orders.track', compact('order'));
    }

    /**
     * Display all custom orders (Admin only).
     */
    public function adminIndex()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $orders = Order::custom()
            ->with(['user', 'artist'])
            ->latest()
            ->paginate(20);

        return view('orders.admin.index', compact('orders'));
    }
}
