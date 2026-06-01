<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'users.view', 'description' => 'View user list and details', 'module' => 'users'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'description' => 'Create new users', 'module' => 'users'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'description' => 'Edit user information', 'module' => 'users'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'description' => 'Delete users', 'module' => 'users'],
            ['name' => 'Approve Users', 'slug' => 'users.approve', 'description' => 'Approve user registrations', 'module' => 'users'],
            ['name' => 'Reject Users', 'slug' => 'users.reject', 'description' => 'Reject user registrations', 'module' => 'users'],

            // Artist Management
            ['name' => 'View Artists', 'slug' => 'artists.view', 'description' => 'View artist list and details', 'module' => 'artists'],
            ['name' => 'Create Artists', 'slug' => 'artists.create', 'description' => 'Create new artists', 'module' => 'artists'],
            ['name' => 'Edit Artists', 'slug' => 'artists.edit', 'description' => 'Edit artist information', 'module' => 'artists'],
            ['name' => 'Delete Artists', 'slug' => 'artists.delete', 'description' => 'Delete artists', 'module' => 'artists'],
            ['name' => 'Approve Artists', 'slug' => 'artists.approve', 'description' => 'Approve artist applications', 'module' => 'artists'],
            ['name' => 'Reject Artists', 'slug' => 'artists.reject', 'description' => 'Reject artist applications', 'module' => 'artists'],

            // Artwork Management
            ['name' => 'View Artworks', 'slug' => 'artworks.view', 'description' => 'View artwork list and details', 'module' => 'artworks'],
            ['name' => 'Create Artworks', 'slug' => 'artworks.create', 'description' => 'Create new artworks', 'module' => 'artworks'],
            ['name' => 'Edit Artworks', 'slug' => 'artworks.edit', 'description' => 'Edit artwork information', 'module' => 'artworks'],
            ['name' => 'Delete Artworks', 'slug' => 'artworks.delete', 'description' => 'Delete artworks', 'module' => 'artworks'],
            ['name' => 'Approve Artworks', 'slug' => 'artworks.approve', 'description' => 'Approve artwork submissions', 'module' => 'artworks'],
            ['name' => 'Reject Artworks', 'slug' => 'artworks.reject', 'description' => 'Reject artwork submissions', 'module' => 'artworks'],

            // Order Management
            ['name' => 'View Orders', 'slug' => 'orders.view', 'description' => 'View order list and details', 'module' => 'orders'],
            ['name' => 'Edit Orders', 'slug' => 'orders.edit', 'description' => 'Edit order information', 'module' => 'orders'],
            ['name' => 'Delete Orders', 'slug' => 'orders.delete', 'description' => 'Delete orders', 'module' => 'orders'],
            ['name' => 'Cancel Orders', 'slug' => 'orders.cancel', 'description' => 'Cancel orders', 'module' => 'orders'],
            ['name' => 'Refund Orders', 'slug' => 'orders.refund', 'description' => 'Process refunds', 'module' => 'orders'],

            // Transaction Management
            ['name' => 'View Transactions', 'slug' => 'transactions.view', 'description' => 'View transaction list and details', 'module' => 'transactions'],
            ['name' => 'Edit Transactions', 'slug' => 'transactions.edit', 'description' => 'Edit transaction information', 'module' => 'transactions'],
            ['name' => 'Delete Transactions', 'slug' => 'transactions.delete', 'description' => 'Delete transactions', 'module' => 'transactions'],

            // Shipment Management
            ['name' => 'View Shipments', 'slug' => 'shipments.view', 'description' => 'View shipment list and details', 'module' => 'shipments'],
            ['name' => 'Create Shipments', 'slug' => 'shipments.create', 'description' => 'Create new shipments', 'module' => 'shipments'],
            ['name' => 'Edit Shipments', 'slug' => 'shipments.edit', 'description' => 'Edit shipment information', 'module' => 'shipments'],
            ['name' => 'Delete Shipments', 'slug' => 'shipments.delete', 'description' => 'Delete shipments', 'module' => 'shipments'],

            // Payout Management
            ['name' => 'View Payouts', 'slug' => 'payouts.view', 'description' => 'View payout list and details', 'module' => 'payouts'],
            ['name' => 'Create Payouts', 'slug' => 'payouts.create', 'description' => 'Create new payouts', 'module' => 'payouts'],
            ['name' => 'Edit Payouts', 'slug' => 'payouts.edit', 'description' => 'Edit payout information', 'module' => 'payouts'],
            ['name' => 'Delete Payouts', 'slug' => 'payouts.delete', 'description' => 'Delete payouts', 'module' => 'payouts'],
            ['name' => 'Process Payouts', 'slug' => 'payouts.process', 'description' => 'Process payouts', 'module' => 'payouts'],
            ['name' => 'Approve Payouts', 'slug' => 'payouts.approve', 'description' => 'Approve payouts', 'module' => 'payouts'],
            ['name' => 'Reject Payouts', 'slug' => 'payouts.reject', 'description' => 'Reject payouts', 'module' => 'payouts'],

            // Commission Management
            ['name' => 'View Commissions', 'slug' => 'commissions.view', 'description' => 'View commission list and details', 'module' => 'commissions'],
            ['name' => 'Edit Commissions', 'slug' => 'commissions.edit', 'description' => 'Edit commission information', 'module' => 'commissions'],
            ['name' => 'Mark Commissions Paid', 'slug' => 'commissions.mark_paid', 'description' => 'Mark commissions as paid', 'module' => 'commissions'],

            // Content Management
            ['name' => 'View Blogs', 'slug' => 'blogs.view', 'description' => 'View blog list and details', 'module' => 'content'],
            ['name' => 'Create Blogs', 'slug' => 'blogs.create', 'description' => 'Create new blogs', 'module' => 'content'],
            ['name' => 'Edit Blogs', 'slug' => 'blogs.edit', 'description' => 'Edit blog information', 'module' => 'content'],
            ['name' => 'Delete Blogs', 'slug' => 'blogs.delete', 'description' => 'Delete blogs', 'module' => 'content'],
            ['name' => 'Publish Blogs', 'slug' => 'blogs.publish', 'description' => 'Publish blogs', 'module' => 'content'],

            ['name' => 'View Exhibitions', 'slug' => 'exhibitions.view', 'description' => 'View exhibition list and details', 'module' => 'content'],
            ['name' => 'Create Exhibitions', 'slug' => 'exhibitions.create', 'description' => 'Create new exhibitions', 'module' => 'content'],
            ['name' => 'Edit Exhibitions', 'slug' => 'exhibitions.edit', 'description' => 'Edit exhibition information', 'module' => 'content'],
            ['name' => 'Delete Exhibitions', 'slug' => 'exhibitions.delete', 'description' => 'Delete exhibitions', 'module' => 'content'],

            ['name' => 'View Collections', 'slug' => 'collections.view', 'description' => 'View collection list and details', 'module' => 'content'],
            ['name' => 'Create Collections', 'slug' => 'collections.create', 'description' => 'Create new collections', 'module' => 'content'],
            ['name' => 'Edit Collections', 'slug' => 'collections.edit', 'description' => 'Edit collection information', 'module' => 'content'],
            ['name' => 'Delete Collections', 'slug' => 'collections.delete', 'description' => 'Delete collections', 'module' => 'content'],

            ['name' => 'View FAQs', 'slug' => 'faqs.view', 'description' => 'View FAQ list and details', 'module' => 'content'],
            ['name' => 'Create FAQs', 'slug' => 'faqs.create', 'description' => 'Create new FAQs', 'module' => 'content'],
            ['name' => 'Edit FAQs', 'slug' => 'faqs.edit', 'description' => 'Edit FAQ information', 'module' => 'content'],
            ['name' => 'Delete FAQs', 'slug' => 'faqs.delete', 'description' => 'Delete FAQs', 'module' => 'content'],

            ['name' => 'View Page Content', 'slug' => 'pages.view', 'description' => 'View page content', 'module' => 'content'],
            ['name' => 'Edit Page Content', 'slug' => 'pages.edit', 'description' => 'Edit page content', 'module' => 'content'],

            // Marketplace Management
            ['name' => 'View Resales', 'slug' => 'resales.view', 'description' => 'View resale list and details', 'module' => 'marketplace'],
            ['name' => 'Approve Resales', 'slug' => 'resales.approve', 'description' => 'Approve resale listings', 'module' => 'marketplace'],
            ['name' => 'Reject Resales', 'slug' => 'resales.reject', 'description' => 'Reject resale listings', 'module' => 'marketplace'],

            // Payment Management
            ['name' => 'View Payment Methods', 'slug' => 'payments.view', 'description' => 'View payment methods', 'module' => 'payments'],
            ['name' => 'Create Payment Methods', 'slug' => 'payments.create', 'description' => 'Create payment methods', 'module' => 'payments'],
            ['name' => 'Edit Payment Methods', 'slug' => 'payments.edit', 'description' => 'Edit payment methods', 'module' => 'payments'],
            ['name' => 'Delete Payment Methods', 'slug' => 'payments.delete', 'description' => 'Delete payment methods', 'module' => 'payments'],

            ['name' => 'View Payment Proofs', 'slug' => 'payment_proofs.view', 'description' => 'View payment proofs', 'module' => 'payments'],
            ['name' => 'Approve Payment Proofs', 'slug' => 'payment_proofs.approve', 'description' => 'Approve payment proofs', 'module' => 'payments'],
            ['name' => 'Reject Payment Proofs', 'slug' => 'payment_proofs.reject', 'description' => 'Reject payment proofs', 'module' => 'payments'],

            ['name' => 'View Promo Codes', 'slug' => 'promo_codes.view', 'description' => 'View promo codes', 'module' => 'payments'],
            ['name' => 'Create Promo Codes', 'slug' => 'promo_codes.create', 'description' => 'Create promo codes', 'module' => 'payments'],
            ['name' => 'Edit Promo Codes', 'slug' => 'promo_codes.edit', 'description' => 'Edit promo codes', 'module' => 'payments'],
            ['name' => 'Delete Promo Codes', 'slug' => 'promo_codes.delete', 'description' => 'Delete promo codes', 'module' => 'payments'],

            // Settings Management
            ['name' => 'View Settings', 'slug' => 'settings.view', 'description' => 'View system settings', 'module' => 'settings'],
            ['name' => 'Edit Settings', 'slug' => 'settings.edit', 'description' => 'Edit system settings', 'module' => 'settings'],

            // Reports & Analytics
            ['name' => 'View Reports', 'slug' => 'reports.view', 'description' => 'View reports and analytics', 'module' => 'reports'],
            ['name' => 'Export Reports', 'slug' => 'reports.export', 'description' => 'Export reports', 'module' => 'reports'],

            // Support Management
            ['name' => 'View Contact Messages', 'slug' => 'support.view', 'description' => 'View contact messages', 'module' => 'support'],
            ['name' => 'Delete Contact Messages', 'slug' => 'support.delete', 'description' => 'Delete contact messages', 'module' => 'support'],
            ['name' => 'Respond to Messages', 'slug' => 'support.respond', 'description' => 'Respond to contact messages', 'module' => 'support'],

            // Activity Logs
            ['name' => 'View Activity Logs', 'slug' => 'logs.view', 'description' => 'View activity logs', 'module' => 'logs'],
            ['name' => 'Export Activity Logs', 'slug' => 'logs.export', 'description' => 'Export activity logs', 'module' => 'logs'],

            // Audit Logs
            ['name' => 'View Audit Logs', 'slug' => 'audit.view', 'description' => 'View audit logs', 'module' => 'audit'],
            ['name' => 'Export Audit Logs', 'slug' => 'audit.export', 'description' => 'Export audit logs', 'module' => 'audit'],

            // Role & Permission Management
            ['name' => 'View Roles', 'slug' => 'roles.view', 'description' => 'View roles', 'module' => 'admin'],
            ['name' => 'Create Roles', 'slug' => 'roles.create', 'description' => 'Create roles', 'module' => 'admin'],
            ['name' => 'Edit Roles', 'slug' => 'roles.edit', 'description' => 'Edit roles', 'module' => 'admin'],
            ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'description' => 'Delete roles', 'module' => 'admin'],
            ['name' => 'Assign Permissions', 'slug' => 'roles.assign_permissions', 'description' => 'Assign permissions to roles', 'module' => 'admin'],

            ['name' => 'View Permissions', 'slug' => 'permissions.view', 'description' => 'View permissions', 'module' => 'admin'],
            ['name' => 'Create Permissions', 'slug' => 'permissions.create', 'description' => 'Create permissions', 'module' => 'admin'],
            ['name' => 'Edit Permissions', 'slug' => 'permissions.edit', 'description' => 'Edit permissions', 'module' => 'admin'],
            ['name' => 'Delete Permissions', 'slug' => 'permissions.delete', 'description' => 'Delete permissions', 'module' => 'admin'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}
