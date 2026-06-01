<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display all users.
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,artist,collector',
            'is_approved' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_approved'] = $request->has('is_approved');

        User::create($validated);

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display pending users.
     */
    public function pendingUsers()
    {
        $users = $this->userRepository->getPending()->paginate(20);

        return view('admin.users.pending', compact('users'));
    }

    /**
     * Show user details.
     */
    public function showUser(User $user)
    {
        $user->load(['artworks', 'transactions', 'orders']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Approve user.
     */
    public function approveUser(User $user)
    {
        $this->userRepository->toggleApproval($user->id);

        return redirect()->back()
            ->with('success', 'User approved successfully.');
    }

    /**
     * Reject user.
     */
    public function rejectUser(User $user)
    {
        $user->update(['is_approved' => false]);

        return redirect()->back()
            ->with('success', 'User rejected successfully.');
    }

    /**
     * Show the form for editing user.
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user.
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,artist,collector',
            'is_approved' => 'boolean',
            'is_active' => 'boolean',
            'bio' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete user.
     */
    public function deleteUser(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Bulk approve users.
     */
    public function bulkApproveUsers(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        foreach ($validated['user_ids'] as $userId) {
            $this->userRepository->toggleApproval($userId);
        }

        return redirect()->back()
            ->with('success', 'Users approved successfully.');
    }

    /**
     * Bulk delete users.
     */
    public function bulkDeleteUsers(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        User::whereIn('id', $validated['user_ids'])->delete();

        return redirect()->back()
            ->with('success', 'Users deleted successfully.');
    }

    /**
     * Export users.
     */
    public function exportUsers()
    {
        $users = User::all();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=users.csv',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Approved', 'Active', 'Created At']);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->is_approved ? 'Yes' : 'No',
                    $user->is_active ? 'Yes' : 'No',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Resend verification email.
     */
    public function resendVerificationEmail(User $user)
    {
        $user->sendEmailVerificationNotification();

        return redirect()->back()
            ->with('success', 'Verification email sent successfully.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleUserStatus(User $user)
    {
        $this->userRepository->toggleActiveStatus($user->id);

        return redirect()->back()
            ->with('success', 'User status updated successfully.');
    }

    /**
     * Impersonate user.
     */
    public function impersonateUser(User $user)
    {
        session()->put('impersonate', $user->id);
        session()->put('impersonate_guard', 'web');

        return redirect()->route('dashboard')
            ->with('success', 'Now impersonating ' . $user->name);
    }

    /**
     * Stop impersonation.
     */
    public function stopImpersonation()
    {
        session()->forget('impersonate');
        session()->forget('impersonate_guard');

        return redirect()->route('admin.dashboard')
            ->with('success', 'Impersonation stopped.');
    }
}
