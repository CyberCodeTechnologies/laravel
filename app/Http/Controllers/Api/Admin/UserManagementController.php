<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserManagementController extends ApiController
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortBy, ['name', 'email', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $users = $query->paginate($request->get('per_page', 15));

        return $this->success($users);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:admin,artist,collector'],
            'status' => ['required', 'string', 'in:pending,approved,rejected,suspended'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'is_approved' => $request->status === 'approved',
            'is_active' => $request->status !== 'suspended',
        ]);

        return $this->success($user, 'User created successfully', 201);
    }

    /**
     * Display the specified user.
     */
    public function show(int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return $this->notFound('User not found');
        }

        return $this->success($user);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return $this->notFound('User not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['sometimes', 'required', 'string', 'in:admin,artist,collector'],
            'status' => ['sometimes', 'required', 'string', 'in:pending,approved,rejected,suspended'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $data = $request->except(['password', 'avatar', 'cover_image']);
        
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        if ($request->has('status')) {
            $data['is_approved'] = $request->status === 'approved';
            $data['is_active'] = $request->status !== 'suspended';
        }

        $user->forceFill($data);
        $user->save();

        return $this->success($user, 'User updated successfully');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return $this->notFound('User not found');
        }

        if ($user->id === auth()->id()) {
            return $this->error('You cannot delete your own account', 403);
        }

        $user->delete();

        return $this->success(null, 'User deleted successfully');
    }

    /**
     * Approve a user.
     */
    public function approve(int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return $this->notFound('User not found');
        }

        $user->update([
            'status' => 'approved',
            'is_approved' => true,
        ]);

        return $this->success($user, 'User approved successfully');
    }

    /**
     * Reject a user.
     */
    public function reject(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return $this->notFound('User not found');
        }

        $user->update([
            'status' => 'rejected',
            'is_approved' => false,
        ]);

        return $this->success($user, 'User rejected successfully');
    }

    /**
     * Bulk actions for users.
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'action' => ['required', 'string', 'in:approve,delete'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $userIds = $request->user_ids;

        if ($request->action === 'approve') {
            User::whereIn('id', $userIds)->update([
                'status' => 'approved',
                'is_approved' => true,
            ]);
            return $this->success(null, 'Users approved successfully');
        }

        if ($request->action === 'delete') {
            // Prevent deleting current user
            $userIds = collect($userIds)->filter(fn($id) => $id != auth()->id())->toArray();
            User::whereIn('id', $userIds)->delete();
            return $this->success(null, 'Users deleted successfully');
        }

        return $this->error('Invalid action');
    }
}
