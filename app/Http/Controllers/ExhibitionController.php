<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use App\Models\Artwork;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExhibitionController extends Controller
{
    /**
     * Display public exhibitions listing.
     */
    public function index(Request $request)
    {
        $query = Exhibition::published()->with(['artworks' => function ($q) {
            $q->approved()->take(4);
        }, 'artists']);
        
        // Filter by status
        if ($request->filled('status')) {
            match($request->status) {
                'upcoming' => $query->upcoming(),
                'ongoing' => $query->ongoing(),
                'completed' => $query->completed(),
                default => null,
            };
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('venue', 'like', '%' . $request->search . '%');
            });
        }
        
        // Sort
        $sort = $request->get('sort', 'latest');
        match($sort) {
            'latest' => $query->latest(),
            'oldest' => $query->oldest(),
            'start_date' => $query->orderBy('start_date', 'asc'),
            'title' => $query->orderBy('title', 'asc'),
            default => $query->latest(),
        };
        
        $exhibitions = $query->paginate(12);
        
        // Get featured exhibition
        $featuredExhibition = Exhibition::published()->featured()->with(['artworks' => function ($q) {
            $q->approved()->take(6);
        }, 'artists'])->first();
        
        return view('exhibitions.index', compact('exhibitions', 'featuredExhibition'));
    }
    
    /**
     * Display exhibition detail page.
     */
    public function show(string $slug)
    {
        $exhibition = Exhibition::where('slug', $slug)
            ->published()
            ->with(['artworks' => function ($q) {
                $q->approved()->orderBy('artwork_exhibition.order');
            }, 'artists'])
            ->firstOrFail();
        
        // Get related exhibitions
        $relatedExhibitions = Exhibition::published()
            ->where('id', '!=', $exhibition->id)
            ->where('status', $exhibition->status)
            ->with(['artworks' => function ($q) {
                $q->approved()->take(3);
            }])
            ->take(3)
            ->get();
        
        return view('exhibitions.show', compact('exhibition', 'relatedExhibitions'));
    }
    
    /**
     * Display admin exhibitions listing.
     */
    public function adminIndex(Request $request)
    {
        $query = Exhibition::with(['artworks', 'artists']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by published
        if ($request->filled('is_published')) {
            $query->where('is_published', $request->boolean('is_published'));
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        $exhibitions = $query->latest()->paginate(20);
        
        return view('admin.exhibitions.index', compact('exhibitions'));
    }
    
    /**
     * Show the form for creating a new exhibition.
     */
    public function create()
    {
        $artworks = Artwork::approved()->get();
        $artists = User::where('role', 'artist')->where('is_approved', true)->get();
        
        return view('admin.exhibitions.create', compact('artworks', 'artists'));
    }
    
    /**
     * Store a newly created exhibition.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'start_date' => ['required', 'date', 'after:today'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'venue' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
            'status' => ['required', 'in:upcoming,ongoing,completed,cancelled'],
            'is_featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'artworks' => ['nullable', 'array'],
            'artworks.*' => ['exists:artworks,id'],
            'artists' => ['nullable', 'array'],
            'artists.*' => ['exists:users,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ]);
        
        $data = $request->except(['images', 'featured_image', 'artworks', 'artists']);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_published'] = $request->has('is_published');
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $featuredImage = $request->file('featured_image');
            $data['featured_image'] = $featuredImage->store('exhibitions', 'public');
        }
        
        // Handle gallery images upload
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('exhibitions', 'public');
                $images[] = $path;
            }
            $data['images'] = $images;
        }
        
        $exhibition = Exhibition::create($data);
        
        // Attach artworks with order
        if ($request->has('artworks')) {
            foreach ($request->artworks as $index => $artworkId) {
                $exhibition->artworks()->attach($artworkId, ['order' => $index]);
            }
        }
        
        // Attach artists
        if ($request->has('artists')) {
            $exhibition->artists()->attach($request->artists);
        }
        
        return redirect()->route('admin.exhibitions.index')
            ->with('success', 'Exhibition created successfully.');
    }
    
    /**
     * Show the form for editing an exhibition.
     */
    public function edit(Exhibition $exhibition)
    {
        $exhibition->load(['artworks', 'artists']);
        $artworks = Artwork::approved()->get();
        $artists = User::where('role', 'artist')->where('is_approved', true)->get();
        
        return view('admin.exhibitions.edit', compact('exhibition', 'artworks', 'artists'));
    }
    
    /**
     * Update an exhibition.
     */
    public function update(Request $request, Exhibition $exhibition)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'venue' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
            'status' => ['required', 'in:upcoming,ongoing,completed,cancelled'],
            'is_featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'artworks' => ['nullable', 'array'],
            'artworks.*' => ['exists:artworks,id'],
            'artists' => ['nullable', 'array'],
            'artists.*' => ['exists:users,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ]);
        
        $data = $request->except(['images', 'featured_image', 'artworks', 'artists']);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_published'] = $request->has('is_published');
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old featured image
            if ($exhibition->featured_image) {
                Storage::disk('public')->delete($exhibition->featured_image);
            }
            $featuredImage = $request->file('featured_image');
            $data['featured_image'] = $featuredImage->store('exhibitions', 'public');
        }
        
        // Handle gallery images upload
        if ($request->hasFile('images')) {
            // Delete old images
            if ($exhibition->images) {
                foreach ($exhibition->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('exhibitions', 'public');
                $images[] = $path;
            }
            $data['images'] = $images;
        }
        
        $exhibition->update($data);
        
        // Sync artworks with order
        if ($request->has('artworks')) {
            $exhibition->artworks()->detach();
            foreach ($request->artworks as $index => $artworkId) {
                $exhibition->artworks()->attach($artworkId, ['order' => $index]);
            }
        } else {
            $exhibition->artworks()->detach();
        }
        
        // Sync artists
        if ($request->has('artists')) {
            $exhibition->artists()->sync($request->artists);
        } else {
            $exhibition->artists()->detach();
        }
        
        return redirect()->route('admin.exhibitions.index')
            ->with('success', 'Exhibition updated successfully.');
    }
    
    /**
     * Delete an exhibition.
     */
    public function destroy(Exhibition $exhibition)
    {
        // Delete images
        if ($exhibition->featured_image) {
            Storage::disk('public')->delete($exhibition->featured_image);
        }
        if ($exhibition->images) {
            foreach ($exhibition->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        
        $exhibition->delete();
        
        return redirect()->route('admin.exhibitions.index')
            ->with('success', 'Exhibition deleted successfully.');
    }
}
