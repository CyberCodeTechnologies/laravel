<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin Role - Has all permissions
        $superAdmin = Role::firstOrCreate(
            ['slug' => 'super_admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Super administrator with full access',
                'is_active' => true,
                'is_default' => false,
            ]
        );

        // Give super admin all permissions
        $allPermissions = Permission::all();
        $superAdmin->syncPermissions($allPermissions->pluck('slug')->toArray());

        // Admin Role - Has most permissions except user/role management
        $admin = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Admin',
                'description' => 'Administrator with standard access',
                'is_active' => true,
                'is_default' => false,
            ]
        );

        $adminPermissions = Permission::whereNotIn('module', ['admin'])->get();
        $admin->syncPermissions($adminPermissions->pluck('slug')->toArray());

        // Artist Manager Role - Can manage artists and artworks
        $artistManager = Role::firstOrCreate(
            ['slug' => 'artist_manager'],
            [
                'name' => 'Artist Manager',
                'description' => 'Can manage artists and artworks',
                'is_active' => true,
                'is_default' => false,
            ]
        );

        $artistManagerPermissions = Permission::whereIn('module', ['artists', 'artworks'])->get();
        $artistManager->syncPermissions($artistManagerPermissions->pluck('slug')->toArray());

        // Content Manager Role - Can manage content
        $contentManager = Role::firstOrCreate(
            ['slug' => 'content_manager'],
            [
                'name' => 'Content Manager',
                'description' => 'Can manage blogs, exhibitions, collections, FAQs',
                'is_active' => true,
                'is_default' => false,
            ]
        );

        $contentManagerPermissions = Permission::whereIn('module', ['content'])->get();
        $contentManager->syncPermissions($contentManagerPermissions->pluck('slug')->toArray());

        // Support Manager Role - Can manage support
        $supportManager = Role::firstOrCreate(
            ['slug' => 'support_manager'],
            [
                'name' => 'Support Manager',
                'description' => 'Can manage support tickets and messages',
                'is_active' => true,
                'is_default' => false,
            ]
        );

        $supportManagerPermissions = Permission::where('module', 'support')->get();
        $supportManager->syncPermissions($supportManagerPermissions->pluck('slug')->toArray());

        // Viewer Role - Read-only access
        $viewer = Role::firstOrCreate(
            ['slug' => 'viewer'],
            [
                'name' => 'Viewer',
                'description' => 'Read-only access to most modules',
                'is_active' => true,
                'is_default' => false,
            ]
        );

        $viewerPermissions = Permission::where('slug', 'like', '%.view')->get();
        $viewer->syncPermissions($viewerPermissions->pluck('slug')->toArray());
    }
}
