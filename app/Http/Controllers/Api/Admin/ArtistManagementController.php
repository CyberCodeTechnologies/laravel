<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ArtistManagementController extends ApiController
{
    /**
     * Display a listing of artists.
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::where('role', 'artist');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        $artists = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success($artists);
    }

    /**
     * Update the specified artist.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $artist = User::where('role', 'artist')->find($id);

        if (!$artist) {
            return $this->notFound('Artist not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($artist->id)],
            'bio' => ['nullable', 'string', 'max:2000'],
            'website' => ['nullable', 'url'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'participate_in_orders' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $data = $request->all();

        if ($request->hasFile('avatar')) {
            if ($artist->avatar) {
                Storage::disk('public')->delete($artist->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($artist->cover_image) {
                Storage::disk('public')->delete($artist->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('artists/covers', 'public');
        }

        $artist->update($data);

        return $this->success($artist, 'Artist updated successfully');
    }

    /**
     * Approve an artist.
     */
    public function approve(int $id): JsonResponse
    {
        $artist = User::where('role', 'artist')->find($id);

        if (!$artist) {
            return $this->notFound('Artist not found');
        }

        $artist->update([
            'is_approved' => true,
            'status' => 'approved'
        ]);

        return $this->success($artist, 'Artist approved successfully');
    }

    /**
     * Reject an artist.
     */
    public function reject(Request $request, int $id): JsonResponse
    {
        $artist = User::where('role', 'artist')->find($id);

        if (!$artist) {
            return $this->notFound('Artist not found');
        }

        $validator = Validator::make($request->all(), [
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $artist->update([
            'is_approved' => false,
            'status' => 'rejected',
            // 'rejection_reason' => $request->reason, // Ensure this column exists if needed
        ]);

        return $this->success($artist, 'Artist rejected successfully');
    }

    /**
     * Remove the specified artist.
     */
    public function destroy(int $id): JsonResponse
    {
        $artist = User::where('role', 'artist')->find($id);

        if (!$artist) {
            return $this->notFound('Artist not found');
        }

        if ($artist->artworks()->exists()) {
            return $this->error('Cannot delete artist with existing artworks', 400);
        }

        $artist->delete();

        return $this->success(null, 'Artist deleted successfully');
    }
}
