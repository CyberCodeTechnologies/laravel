<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleManagementController extends ApiController
{
    /**
     * Display a listing of roles.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Role::with('permissions');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $roles = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success($roles);
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:roles'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $role = Role::create($request->all());

        return $this->success($role, 'Role created successfully', 201);
    }

    /**
     * Display the specified role.
     */
    public function show(int $id): JsonResponse
    {
        $role = Role::with('permissions', 'users')->find($id);

        if (!$role) {
            return $this->notFound('Role not found');
        }

        return $this->success($role);
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $role = Role::find($id);

        if (!$role) {
            return $this->notFound('Role not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'unique:roles,slug,' . $id],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $role->update($request->all());

        return $this->success($role, 'Role updated successfully');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(int $id): JsonResponse
    {
        $role = Role::find($id);

        if (!$role) {
            return $this->notFound('Role not found');
        }

        if ($role->is_default) {
            return $this->error('Cannot delete default role', 400);
        }

        $role->delete();

        return $this->success(null, 'Role deleted successfully');
    }

    /**
     * Assign permissions to a role.
     */
    public function assignPermissions(Request $request, int $id): JsonResponse
    {
        $role = Role::find($id);

        if (!$role) {
            return $this->notFound('Role not found');
        }

        $validator = Validator::make($request->all(), [
            'permission_ids' => ['required', 'array'],
            'permission_ids.*' => ['exists:permissions,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $role->syncPermissions($request->permission_ids);

        return $this->success($role->load('permissions'), 'Permissions assigned successfully');
    }

    /**
     * Get available permissions for a role.
     */
    public function availablePermissions(Request $request): JsonResponse
    {
        $query = Permission::active();

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        $permissions = $query->orderBy('module')->orderBy('name')->get();

        return $this->success($permissions);
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(int $id): JsonResponse
    {
        $role = Role::find($id);

        if (!$role) {
            return $this->notFound('Role not found');
        }

        if ($role->is_default) {
            return $this->error('Cannot toggle default role', 400);
        }

        $role->update(['is_active' => !$role->is_active]);

        return $this->success($role, 'Active status toggled successfully');
    }
}
