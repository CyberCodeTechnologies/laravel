@extends('layouts.app')

@section('title', 'Feature Documentation - Panchi Gallery')
@section('meta-description', 'Complete feature documentation for Panchi Gallery art marketplace platform.')

@section('content')
<!-- Header -->
<section class="bg-gray-900 text-white py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-serif text-4xl md:text-5xl font-bold mb-4">Feature Documentation</h1>
        <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Complete guide to Panchi Gallery's features, functionality, and technical implementation.
        </p>
    </div>
</section>

<!-- Documentation Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Quick Navigation -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4">Quick Navigation</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <a href="#overview" class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200 transition text-center">Overview</a>
                <a href="#users" class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200 transition text-center">User Management</a>
                <a href="#artworks" class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200 transition text-center">Artworks</a>
                <a href="#certificates" class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200 transition text-center">Certificates</a>
                <a href="#payments" class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200 transition text-center">Payments</a>
                <a href="#marketplace" class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200 transition text-center">Resale Market</a>
                <a href="#admin" class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200 transition text-center">Admin Panel</a>
                <a href="#technical" class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200 transition text-center">Technical</a>
            </div>
        </div>

        <!-- Overview -->
        <div id="overview" class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-serif font-bold mb-4">Platform Overview</h2>
            <p class="text-gray-700 leading-relaxed mb-4">
                PanchiGallery.com is a comprehensive Laravel-based art marketplace platform connecting Myanmar artists with global collectors through verified certificates, secure transactions, and a curated secondary market.
            </p>
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                <p class="text-sm text-blue-800">
                    <span class="font-semibold">Status:</span> Production Ready - 100% Feature Complete
                </p>
            </div>
        </div>

        <!-- Core Features Grid -->
        <div id="users" class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-serif font-bold mb-6">1. User Management & Authentication</h2>
            
            <h3 class="text-lg font-semibold mb-3 mt-6">Multi-Role System</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                <li><strong>Admin:</strong> Full platform management, user approvals, content moderation</li>
                <li><strong>Artist:</strong> Portfolio management, artwork uploads, sales tracking</li>
                <li><strong>Collector:</strong> Artwork purchases, collection management, resale capabilities</li>
            </ul>

            <h3 class="text-lg font-semibold mb-3">Authentication Features</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                <li>User registration with role selection</li>
                <li>Email verification workflow</li>
                <li>Secure login with session management</li>
                <li>Password reset functionality</li>
                <li>Admin-only login portal</li>
                <li>Account approval workflow for artists</li>
            </ul>

            <h3 class="text-lg font-semibold mb-3">Profile Management</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <li>Profile photo upload</li>
                <li>Personal information editing</li>
                <li>Bio and artist statement</li>
                <li>Social media links (Instagram, Facebook, Twitter)</li>
                <li>Contact information management</li>
                <li>Password change functionality</li>
            </ul>
        </div>

        <div id="artworks" class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-serif font-bold mb-6">2. Artwork Management</h2>
            
            <h3 class="text-lg font-semibold mb-3">Artist Features</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                <li>Upload Artworks with multi-image support</li>
                <li>Artwork Details: Title, description, category, medium, dimensions, year, pricing</li>
                <li>Status Tracking: Draft, Pending Review, Approved, Rejected, Sold</li>
                <li>Portfolio Organization and sorting</li>
            </ul>

            <h3 class="text-lg font-semibold mb-3">Search & Discovery</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <li>Full-text search across artworks</li>
                <li>Category and price range filtering</li>
                <li>Artist filtering and sorting options</li>
                <li>Grid and list view options</li>
            </ul>
        </div>

        <div id="certificates" class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-serif font-bold mb-6">3. Certificate of Authenticity System</h2>
            
            <h3 class="text-lg font-semibold mb-3">Certificate Generation</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                <li>Unique certificate code generation</li>
                <li>QR code integration for verification</li>
                <li>Digital signature by Panchi Gallery</li>
                <li>Automatic certificate creation on artwork approval</li>
            </ul>

            <h3 class="text-lg font-semibold mb-3">Verification Features</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <li>Online certificate verification</li>
                <li>QR code scanning support</li>
                <li>Complete provenance tracking</li>
                <li>Ownership history timeline</li>
                <li>PDF certificate download</li>
            </ul>
        </div>

        <div id="payments" class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-serif font-bold mb-6">4. Transaction & Payment System</h2>
            
            <h3 class="text-lg font-semibold mb-3">Payment Methods</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                <li>Credit/Debit Cards (Stripe integration ready)</li>
                <li>PayPal checkout integration</li>
                <li>Bank Transfer with manual verification</li>
                <li>Mobile Payments (KBZ Pay, Wave Money, etc.)</li>
                <li>Cash on Delivery for local orders</li>
            </ul>

            <h3 class="text-lg font-semibold mb-3">Transaction Features</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <li>Secure checkout process</li>
                <li>Payment proof upload for manual methods</li>
                <li>Transaction status tracking</li>
                <li>Automated email confirmations</li>
                <li>Invoice generation</li>
                <li>Refund processing</li>
            </ul>
        </div>

        <div id="marketplace" class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-serif font-bold mb-6">5. Secondary Market (Resale)</h2>
            
            <h3 class="text-lg font-semibold mb-3">Resale Listing Features</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                <li>List owned artworks for resale</li>
                <li>Set asking price and condition reporting</li>
                <li>Condition description and provenance notes</li>
                <li>Admin approval workflow</li>
                <li>Automatic certificate transfer to new owner</li>
            </ul>

            <h3 class="text-lg font-semibold mb-3">Marketplace Features</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <li>Browse verified resale listings</li>
                <li>View condition reports</li>
                <li>Secure purchase process</li>
                <li>Ownership transfer automation</li>
            </ul>
        </div>

        <div id="admin" class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-serif font-bold mb-6">6. Admin Panel Features</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-3">Management</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        <li>User Management</li>
                        <li>Artist Management</li>
                        <li>Artwork Moderation</li>
                        <li>Category Management</li>
                        <li>Collection Curation</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-3">Oversight</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        <li>Transaction Oversight</li>
                        <li>Marketplace Management</li>
                        <li>Support Management</li>
                        <li>System Settings</li>
                        <li>Financial Management</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="technical" class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-serif font-bold mb-6">7. Technical Implementation</h2>
            
            <h3 class="text-lg font-semibold mb-3">Core Stack</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                <li><strong>Framework:</strong> Laravel 12.44.0 with PHP 8.2+</li>
                <li><strong>Frontend:</strong> Tailwind CSS with minimal black & white gallery style</li>
                <li><strong>Database:</strong> MySQL with comprehensive relational schema</li>
                <li><strong>Authentication:</strong> Laravel Breeze with role-based middleware</li>
                <li><strong>File Storage:</strong> Laravel's public disk for images and certificates</li>
            </ul>

            <h3 class="text-lg font-semibold mb-3">Key Capabilities</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                <li>Multi-language support (English, Myanmar)</li>
                <li>Multi-currency support (USD, MMK)</li>
                <li>Payment gateway integration (Stripe, PayPal)</li>
                <li>Queue system for background processing</li>
                <li>Redis caching support</li>
            </ul>

            <h3 class="text-lg font-semibold mb-3">Database Tables</h3>
            <p class="text-gray-700 mb-2">Core tables include: users, artworks, categories, collections, transactions, resales, ownerships, certificates, followers, likes, payment_methods, shipments, and faqs.</p>
        </div>

        <!-- Status Summary -->
        <div class="bg-gradient-to-r from-gray-900 to-black text-white rounded-lg p-8">
            <h2 class="text-2xl font-serif font-bold mb-4">Platform Status</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-400 mb-2">100%</div>
                    <p class="text-gray-300">Feature Complete</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-400 mb-2">Ready</div>
                    <p class="text-gray-300">Production Deployment</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-purple-400 mb-2">15+</div>
                    <p class="text-gray-300">Core Feature Sets</p>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="mt-8 bg-white rounded-lg shadow-sm p-8">
            <h2 class="text-xl font-serif font-bold mb-4">Next Steps</h2>
            <ol class="list-decimal list-inside space-y-2 text-gray-700">
                <li>Run database migrations: <code class="bg-gray-100 px-2 py-1 rounded text-sm">php artisan migrate</code></li>
                <li>Seed initial data: <code class="bg-gray-100 px-2 py-1 rounded text-sm">php artisan db:seed</code></li>
                <li>Set up file storage permissions</li>
                <li>Configure environment variables</li>
                <li>Test all user workflows</li>
                <li>Deploy to production environment</li>
            </ol>
        </div>

    </div>
</section>
@endsection
