<?php

namespace App\Http\Controllers;

use App\Models\Ownership;
use App\Models\Artwork;
use Illuminate\Http\Request;

class OwnershipController extends Controller
{
    /**
     * Display ownership history for an artwork.
     */
    public function history(Artwork $artwork)
    {
        $ownerships = $artwork->ownerships()
            ->with('owner')
            ->orderBy('acquired_at', 'asc')
            ->get();
        
        return view('artworks.ownership-history', compact('artwork', 'ownerships'));
    }

    /**
     * Display current ownerships for authenticated user.
     */
    public function myOwnerships()
    {
        $ownerships = auth()->user()->ownerships()
            ->with('artwork.artist', 'artwork.category')
            ->where('is_current_owner', true)
            ->latest('acquired_at')
            ->paginate(12);
        
        return view('ownership.my-artworks', compact('ownerships'));
    }

    /**
     * Transfer ownership of an artwork.
     */
    public function transfer(Request $request, Artwork $artwork)
    {
        $this->authorize('transfer', $artwork);
        
        $request->validate([
            'recipient_email' => ['required', 'email', 'exists:users,email'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);
        
        $recipient = \App\Models\User::where('email', $request->recipient_email)->first();
        
        if (!$recipient) {
            return back()->with('error', 'Recipient not found.');
        }
        
        if ($recipient->id === auth()->id()) {
            return back()->with('error', 'Cannot transfer artwork to yourself.');
        }
        
        // Get current ownership
        $currentOwnership = $artwork->ownerships()
            ->where('owner_id', auth()->id())
            ->where('is_current_owner', true)
            ->first();
        
        if (!$currentOwnership) {
            return back()->with('error', 'You are not the current owner of this artwork.');
        }
        
        // Transfer ownership
        $newOwnership = $currentOwnership->transferTo(
            $recipient,
            null, // Free transfer
            'transfer'
        );
        
        // Create notification for recipient
        // This would be implemented with a notification system
        
        return redirect()->route('ownership.my-artworks')
            ->with('success', 'Artwork transferred successfully to ' . $recipient->name);
    }

    /**
     * Verify ownership before listing for resale.
     */
    public function verifyOwnership(Artwork $artwork)
    {
        $user = auth()->user();
        
        $ownership = $artwork->ownerships()
            ->where('owner_id', $user->id)
            ->where('is_current_owner', true)
            ->first();
        
        if (!$ownership) {
            return response()->json([
                'verified' => false,
                'message' => 'You are not the current owner of this artwork.',
            ], 403);
        }
        
        return response()->json([
            'verified' => true,
            'ownership' => [
                'acquired_at' => $ownership->acquired_at,
                'purchase_price' => $ownership->purchase_price,
                'transaction_type' => $ownership->transaction_type,
            ],
        ]);
    }

    /**
     * Get ownership certificate.
     */
    public function certificate(Ownership $ownership)
    {
        $this->authorize('view', $ownership);
        
        return view('ownership.certificate', compact('ownership'));
    }

    /**
     * Generate ownership transfer document.
     */
    public function generateTransferDocument(Ownership $ownership)
    {
        $this->authorize('view', $ownership);
        
        // Generate PDF transfer document
        $pdf = PDF::loadView('ownership.transfer-document', compact('ownership'));
        
        return $pdf->download("ownership-transfer-{$ownership->id}.pdf");
    }

    /**
     * API endpoint to get ownership history.
     */
    public function historyApi(Artwork $artwork)
    {
        $ownerships = $artwork->ownerships()
            ->with('owner:id,name,email')
            ->orderBy('acquired_at', 'asc')
            ->get()
            ->map(function ($ownership) {
                return [
                    'owner' => [
                        'id' => $ownership->owner->id,
                        'name' => $ownership->owner->name,
                    ],
                    'acquired_at' => $ownership->acquired_at->format('Y-m-d H:i:s'),
                    'purchase_price' => $ownership->purchase_price,
                    'transaction_type' => $ownership->transaction_type,
                    'is_current_owner' => $ownership->is_current_owner,
                    'notes' => $ownership->notes,
                ];
            });
        
        return response()->json([
            'artwork' => [
                'id' => $artwork->id,
                'title' => $artwork->title,
                'artist' => $artwork->artist->name,
            ],
            'ownerships' => $ownerships,
        ]);
    }
}
