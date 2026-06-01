<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionManagementController extends ApiController
{
    /**
     * Display a listing of permissions.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Permission::with('roles');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $permissions = $query->orderBy('module')->orderBy('name')->paginate($request->get('per_page', 15));

        return $this->success($permissions);
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:permissions'],
            'description' => ['nullable', 'string', 'max:500'],
            'module' => ['required', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $permission = Permission::create($request->all());

        return $this->success($permission, 'Permission created successfully', 201);
    }

    /**
     * Display the specified permission.
     */
    public function show(int $id): JsonResponse
    {
        $permission = Permission::with('roles')->find($id);

        if (!$permission) {
            return $this->notFound('Permission not found');
        }

        return $this->success($permission);
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return $this->notFound('Permission not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'unique:permissions,slug,' . $id],
            'description' => ['nullable', 'string', 'max:500'],
            'module' => ['sometimes', 'required', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $permission->update($request->all());

        return $this->success($permission, 'Permission updated successfully');
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(int $id): JsonResponse
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return $this->notFound('Permission not found');
        }

        if ($permission->roles()->count() > 0) {
            return $this->error('Cannot delete permission assigned to roles', 400);
        }

        $permission->delete();

        return $this->success(null, 'Permission deleted successfully');
    }

    /**
     * Get all modules.
     */
    public function modules(): JsonResponse
    {
        $modules = Permission::select('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        return $this->success($modules);
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(int $id): JsonResponse
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return $this->notFound('Permission not found');
        }

        $permission->update(['is_active' => !$permission->is_active]);

        return $this->success($permission, 'Active status toggled successfully');
    }
}
