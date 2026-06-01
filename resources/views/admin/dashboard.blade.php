@extends('admin.layouts.app')

@section('title', 'Dashboard - Panchi Gallery')

@section('header', 'Dashboard')

@push('styles')
<style>
.zoho-content .dashboard-container {
    padding: 20px;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.dashboard-header h1 {
    font-size: 24px;
    font-weight: 600;
    color: var(--zoho-text-primary);
    margin: 0 0 4px 0;
}

.dashboard-header p {
    color: var(--zoho-text-secondary);
    margin: 0;
    font-size: 13px;
}

.refresh-btn {
    background: var(--zoho-blue);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: var(--zoho-radius);
    cursor: pointer;
    font-weight: 500;
    font-size: 13px;
    transition: all 0.2s ease;
}

.refresh-btn:hover {
    background: var(--zoho-blue-hover);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--zoho-bg-card);
    border: 1px solid var(--zoho-border);
    border-radius: var(--zoho-radius-lg);
    padding: 14px 18px;
    transition: all 0.2s ease;
    min-height: 96px;
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 12px;
}

.stat-card:hover {
    border-color: var(--zoho-blue);
    box-shadow: var(--zoho-shadow);
}

.stat-card.purple { border-top: 3px solid var(--zoho-blue); }
.stat-card.blue { border-top: 3px solid #4facfe; }
.stat-card.green { border-top: 3px solid var(--zoho-success); }
.stat-card.orange { border-top: 3px solid var(--zoho-warning); }

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-icon.purple { background: var(--zoho-blue-light); color: var(--zoho-blue); }
.stat-icon.blue { background: #e8f4fd; color: #4facfe; }
.stat-icon.green { background: #d1fae5; color: var(--zoho-success); }
.stat-icon.orange { background: #fef3c7; color: var(--zoho-warning); }

.stat-value {
    font-size: 20px;
    font-weight: 700;
    color: var(--zoho-text-primary);
}

.stat-label {
    color: var(--zoho-text-secondary);
    font-size: 12px;
    font-weight: 600;
}

.stat-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.stat-change {
    font-size: 12px;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.stat-change.positive { color: var(--zoho-success); }
.stat-change.negative { color: var(--zoho-danger); }
.stat-change.neutral { color: var(--zoho-text-muted); }

.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 16px;
    margin-bottom: 24px;
}

@media (max-width: 1200px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 900px) {
    .stat-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
        min-height: 120px;
    }
    .stat-meta { align-items: center; }
}

.card {
    background: var(--zoho-bg-card);
    border: 1px solid var(--zoho-border);
    border-radius: var(--zoho-radius-lg);
    overflow: hidden;
}

.card-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--zoho-border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    font-size: 14px;
    font-weight: 600;
    color: var(--zoho-text-primary);
    margin: 0;
}

.card-header .actions {
    display: flex;
    gap: 8px;
}

.card-header .btn-sm {
    padding: 4px 12px;
    font-size: 12px;
    border-radius: var(--zoho-radius);
    border: 1px solid var(--zoho-border);
    background: var(--zoho-bg-card);
    cursor: pointer;
    transition: all 0.2s ease;
    color: var(--zoho-text-secondary);
}

.card-header .btn-sm:hover {
    background: var(--zoho-blue-light);
    color: var(--zoho-blue);
    border-color: var(--zoho-blue);
}

.card-header .btn-sm.active {
    background: var(--zoho-blue);
    color: white;
    border-color: var(--zoho-blue);
}

.card-body {
    padding: 20px;
}

.chart-container {
    height: 300px;
    position: relative;
}

.chart-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    background: var(--zoho-bg-page);
    border-radius: var(--zoho-radius);
    color: var(--zoho-text-muted);
    font-size: 13px;
}

#salesChart {
    max-height: 300px;
}

.list-item {
    display: flex;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid var(--zoho-border-light);
}

.list-item:last-child {
    border-bottom: none;
}

.list-item-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--zoho-blue-light);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--zoho-blue);
    font-weight: 600;
    font-size: 13px;
    margin-right: 12px;
}

.list-item-content {
    flex: 1;
}

.list-item-title {
    font-weight: 600;
    color: var(--zoho-text-primary);
    margin-bottom: 2px;
    font-size: 13px;
}

.list-item-subtitle {
    font-size: 12px;
    color: var(--zoho-text-muted);
}

.list-item-meta {
    text-align: right;
}

.list-item-value {
    font-weight: 600;
    color: var(--zoho-text-primary);
    font-size: 13px;
}

.list-item-time {
    font-size: 11px;
    color: var(--zoho-text-muted);
}

.badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.badge-success { background: #d1fae5; color: #065f46; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-danger { background: #fee2e2; color: #991b1b; }
.badge-info { background: #dbeafe; color: #1e40af; }
.badge-purple { background: #ede9fe; color: #5b21b6; }

.pending-section {
    background: #fffbeb;
    border: 1px solid #fcd34d;
    border-radius: var(--zoho-radius-lg);
    padding: 16px;
    margin-bottom: 24px;
}

.pending-section h4 {
    color: #92400e;
    margin: 0 0 12px 0;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.pending-items {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
}

.pending-item {
    background: white;
    border-radius: var(--zoho-radius);
    padding: 12px;
    border: 1px solid #fcd34d;
}

.pending-item-count {
    font-size: 20px;
    font-weight: 700;
    color: #92400e;
}

.pending-item-label {
    font-size: 12px;
    color: var(--zoho-text-muted);
    margin-top: 4px;
}

.pending-item-action {
    margin-top: 8px;
}

.pending-item-action a {
    color: #92400e;
    font-size: 12px;
    font-weight: 500;
    text-decoration: none;
}

.pending-item-action a:hover {
    text-decoration: underline;
}

.secondary-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 24px;
}

@media (max-width: 1200px) {
    .secondary-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.secondary-stat-card {
    background: var(--zoho-bg-card);
    border: 1px solid var(--zoho-border);
    border-radius: var(--zoho-radius-lg);
    padding: 16px;
    text-align: center;
}

.secondary-stat-value {
    font-size: 24px;
    font-weight: 600;
    color: var(--zoho-text-primary);
    margin-bottom: 4px;
}

.secondary-stat-label {
    font-size: 12px;
    color: var(--zoho-text-secondary);
    font-weight: 500;
}

.secondary-stat-sub {
    font-size: 11px;
    color: var(--zoho-text-muted);
    margin-top: 4px;
}

.system-health {
    background: var(--zoho-bg-card);
    border: 1px solid var(--zoho-border);
    border-radius: var(--zoho-radius-lg);
    padding: 16px;
}

.system-health h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--zoho-text-primary);
    margin: 0 0 12px 0;
}

.system-health-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid var(--zoho-border-light);
}

.system-health-item:last-child {
    border-bottom: none;
}

.system-health-label {
    font-size: 12px;
    color: var(--zoho-text-muted);
}

.system-health-value {
    font-size: 12px;
    font-weight: 600;
    color: var(--zoho-text-primary);
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: var(--zoho-border-light);
    border-radius: 3px;
    overflow: hidden;
    margin-top: 6px;
}

.progress-bar-fill {
    height: 100%;
    background: var(--zoho-blue);
    border-radius: 3px;
    transition: width 0.3s ease;
}

.quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 24px;
}

.quick-action-btn {
    background: var(--zoho-bg-card);
    border: 1px solid var(--zoho-border);
    border-radius: var(--zoho-radius);
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 500;
    color: var(--zoho-text-primary);
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}

.quick-action-btn:hover {
    background: var(--zoho-blue-light);
    border-color: var(--zoho-blue);
    color: var(--zoho-blue);
}

.quick-action-btn i {
    font-size: 14px;
}

.empty-state {
    text-align: center;
    padding: 32px 16px;
    color: var(--zoho-text-muted);
}

.empty-state i {
    font-size: 36px;
    margin-bottom: 12px;
    opacity: 0.5;
}

.empty-state p {
    font-size: 13px;
    margin: 0;
}
</style>
@endpush

@section('admin_content')
<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div>
            <h1>Dashboard</h1>
            <p>Welcome back! Here's what's happening with your gallery today.</p>
        </div>
        <button class="refresh-btn" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>

    <!-- Primary Stats -->
    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon purple">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-value">${{ number_format($revenueThisMonth ?? 0, 2) }}</div>
            <div class="stat-label">Revenue This Month</div>
            <div class="stat-change {{ $revenueGrowth >= 0 ? 'positive' : 'negative' }}">
                <i class="fas fa-{{ $revenueGrowth >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                {{ number_format(abs($revenueGrowth), 1) }}% vs last month
            </div>
        </div>

        <div class="stat-card blue">
            <div class="stat-icon blue">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_users'] ?? 0) }}</div>
            <div class="stat-label">Total Users</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                +{{ $engagementStats['new_users_this_month'] ?? 0 }} this month
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon green">
                <i class="fas fa-palette"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_artworks'] ?? 0) }}</div>
            <div class="stat-label">Total Artworks</div>
            <div class="stat-change neutral">
                {{ $secondaryStats['approved_artworks'] ?? 0 }} approved
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-icon orange">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-value">{{ number_format($secondaryStats['completed_transactions'] ?? 0) }}</div>
            <div class="stat-label">Completed Transactions</div>
            <div class="stat-change neutral">
                {{ $secondaryStats['pending_transactions'] ?? 0 }} pending
            </div>
        </div>
    </div>

    <!-- Pending Items Alert -->
    @if($stats['pending_artists'] > 0 || $stats['pending_artworks'] > 0 || $stats['pending_resales'] > 0)
    <div class="pending-section">
        <h4>
            <i class="fas fa-exclamation-triangle"></i>
            Items Requiring Your Attention
        </h4>
        <div class="pending-items">
            @if($stats['pending_artists'] > 0)
            <div class="pending-item">
                <div class="pending-item-count">{{ $stats['pending_artists'] }}</div>
                <div class="pending-item-label">Pending Artists</div>
                <div class="pending-item-action">
                    <a href="{{ route('admin.pending-artists') }}">Review →</a>
                </div>
            </div>
            @endif

            @if($stats['pending_artworks'] > 0)
            <div class="pending-item">
                <div class="pending-item-count">{{ $stats['pending_artworks'] }}</div>
                <div class="pending-item-label">Pending Artworks</div>
                <div class="pending-item-action">
                    <a href="{{ route('admin.artworks.pending') }}">Review →</a>
                </div>
            </div>
            @endif

            @if($stats['pending_resales'] > 0)
            <div class="pending-item">
                <div class="pending-item-count">{{ $stats['pending_resales'] }}</div>
                <div class="pending-item-label">Pending Resales</div>
                <div class="pending-item-action">
                    <a href="{{ route('admin.marketplace.pending') }}">Review →</a>
                </div>
            </div>
            @endif

            @if(isset($pendingItems['payment_proofs']) && $pendingItems['payment_proofs']->count() > 0)
            <div class="pending-item">
                <div class="pending-item-count">{{ $pendingItems['payment_proofs']->count() }}</div>
                <div class="pending-item-label">Payment Proofs</div>
                <div class="pending-item-action">
                    <a href="{{ route('admin.payments.verifications') }}">Review →</a>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Secondary Stats -->
    <div class="secondary-stats-grid">
        <div class="secondary-stat-card">
            <div class="secondary-stat-value">{{ $secondaryStats['total_artists'] ?? 0 }}</div>
            <div class="secondary-stat-label">Active Artists</div>
            <div class="secondary-stat-sub">Approved & verified</div>
        </div>

        <div class="secondary-stat-card">
            <div class="secondary-stat-value">{{ $secondaryStats['total_collectors'] ?? 0 }}</div>
            <div class="secondary-stat-label">Collectors</div>
            <div class="secondary-stat-sub">Registered buyers</div>
        </div>

        <div class="secondary-stat-card">
            <div class="secondary-stat-value">{{ $secondaryStats['active_resales'] ?? 0 }}</div>
            <div class="secondary-stat-label">Active Resales</div>
            <div class="secondary-stat-sub">Listed on marketplace</div>
        </div>

        <div class="secondary-stat-card">
            <div class="secondary-stat-value">${{ number_format($secondaryStats['average_order_value'] ?? 0, 2) }}</div>
            <div class="secondary-stat-label">Avg Order Value</div>
            <div class="secondary-stat-sub">Per transaction</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="{{ route('admin.users.create') }}" class="quick-action-btn">
            <i class="fas fa-user-plus"></i> New User
        </a>
        <a href="{{ route('admin.artworks.create') }}" class="quick-action-btn">
            <i class="fas fa-plus-circle"></i> New Artwork
        </a>
        <a href="{{ route('admin.artworks.pending') }}" class="quick-action-btn">
            <i class="fas fa-clock"></i> Pending Artworks
        </a>
        <a href="{{ route('admin.pending-artists') }}" class="quick-action-btn">
            <i class="fas fa-user-check"></i> Pending Artists
        </a>
        <a href="{{ route('admin.reports.dashboard') }}" class="quick-action-btn">
            <i class="fas fa-chart-bar"></i> Reports
        </a>
        <a href="{{ route('admin.settings') }}" class="quick-action-btn">
            <i class="fas fa-cog"></i> Settings
        </a>
        <a href="{{ route('admin.frontend.dashboard') }}" class="quick-action-btn">
            <i class="fas fa-paint-brush"></i> Frontend
        </a>
        <a href="{{ route('admin.support') }}" class="quick-action-btn">
            <i class="fas fa-headset"></i> Support
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Left Column -->
        <div>
            <!-- Sales Chart -->
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <h3>Sales Overview (Last 6 Months)</h3>
                    <div class="actions">
                        <button class="btn-sm active">Revenue</button>
                        <button class="btn-sm">Transactions</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card">
                <div class="card-header">
                    <h3>Recent Transactions</h3>
                    <a href="{{ route('admin.transactions') }}" style="font-size: 13px; color: #667eea; text-decoration: none;">View All</a>
                </div>
                <div class="card-body">
                    @if(isset($recentTransactions) && $recentTransactions->count() > 0)
                        @foreach($recentTransactions as $transaction)
                        <div class="list-item">
                            <div class="list-item-avatar">
                                {{ substr($transaction->buyer->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="list-item-content">
                                <div class="list-item-title">{{ $transaction->buyer->name ?? 'Unknown' }}</div>
                                <div class="list-item-subtitle">{{ $transaction->artwork->title ?? 'Artwork' }}</div>
                            </div>
                            <div class="list-item-meta">
                                <div class="list-item-value">${{ number_format($transaction->amount ?? 0, 2) }}</div>
                                <div class="list-item-time">{{ $transaction->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-receipt"></i>
                            <p>No recent transactions</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div>
            <!-- Engagement Metrics -->
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <h3>Engagement Metrics</h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div style="text-align: center; padding: 16px; background: #f9fafb; border-radius: 8px;">
                            <div style="font-size: 24px; font-weight: 700; color: #667eea;">{{ number_format($engagementStats['total_likes'] ?? 0) }}</div>
                            <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Total Likes</div>
                        </div>
                        <div style="text-align: center; padding: 16px; background: #f9fafb; border-radius: 8px;">
                            <div style="font-size: 24px; font-weight: 700; color: #10b981;">{{ number_format($engagementStats['total_wishlists'] ?? 0) }}</div>
                            <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Wishlists</div>
                        </div>
                        <div style="text-align: center; padding: 16px; background: #f9fafb; border-radius: 8px;">
                            <div style="font-size: 24px; font-weight: 700; color: #f59e0b;">{{ number_format($engagementStats['active_carts'] ?? 0) }}</div>
                            <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Active Carts</div>
                        </div>
                        <div style="text-align: center; padding: 16px; background: #f9fafb; border-radius: 8px;">
                            <div style="font-size: 24px; font-weight: 700; color: #8b5cf6;">{{ number_format($engagementStats['total_views'] ?? 0) }}</div>
                            <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Total Views</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Categories -->
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <h3>Top Categories</h3>
                </div>
                <div class="card-body">
                    @if(isset($topCategories) && $topCategories->count() > 0)
                        @foreach($topCategories as $index => $category)
                        <div style="margin-bottom: 16px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                <span style="font-size: 13px; font-weight: 500; color: #1a1a2e;">{{ $category->name }}</span>
                                <span style="font-size: 13px; color: #6b7280;">{{ $category->artworks_count }}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-bar-fill" style="width: {{ ($topCategories->first()->artworks_count ?? 0) > 0 ? ($category->artworks_count / $topCategories->first()->artworks_count) * 100 : 0 }}%;"></div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-folder"></i>
                            <p>No categories yet</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- System Health -->
            <div class="system-health">
                <h4>System Health</h4>
                <div class="system-health-item">
                    <span class="system-health-label">Disk Usage</span>
                    <span class="system-health-value">{{ $systemHealth['disk_usage']['percentage'] ?? 0 }}%</span>
                </div>
                <div class="system-health-item">
                    <span class="system-health-label">Cache Driver</span>
                    <span class="system-health-value">{{ $systemHealth['cache_status'] ?? 'N/A' }}</span>
                </div>
                <div class="system-health-item">
                    <span class="system-health-label">PHP Version</span>
                    <span class="system-health-value">{{ $systemHealth['php_version'] ?? 'N/A' }}</span>
                </div>
                <div class="system-health-item">
                    <span class="system-health-label">Laravel Version</span>
                    <span class="system-health-value">{{ $systemHealth['laravel_version'] ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users & Artworks -->
    <div class="content-grid">
        <div>
            <div class="card">
                <div class="card-header">
                    <h3>Recent Users</h3>
                    <a href="{{ route('admin.users') }}" style="font-size: 13px; color: #667eea; text-decoration: none;">View All</a>
                </div>
                <div class="card-body">
                    @if(isset($recentUsers) && $recentUsers->count() > 0)
                        @foreach($recentUsers as $user)
                        <div class="list-item">
                            <div class="list-item-avatar">
                                {{ substr($user->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="list-item-content">
                                <div class="list-item-title">{{ $user->name ?? 'Unknown' }}</div>
                                <div class="list-item-subtitle">{{ $user->email ?? '' }}</div>
                            </div>
                            <div class="list-item-meta">
                                <span class="badge badge-{{ $user->role === 'artist' ? 'purple' : ($user->role === 'admin' ? 'danger' : 'info') }}">
                                    {{ ucfirst($user->role ?? 'user') }}
                                </span>
                                <div class="list-item-time">{{ $user->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>No recent users</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <h3>Recent Artworks</h3>
                    <a href="{{ route('admin.artworks') }}" style="font-size: 13px; color: #667eea; text-decoration: none;">View All</a>
                </div>
                <div class="card-body">
                    @if(isset($recentArtworks) && $recentArtworks->count() > 0)
                        @foreach($recentArtworks as $artwork)
                        <div class="list-item">
                            <div class="list-item-avatar">
                                <i class="fas fa-image"></i>
                            </div>
                            <div class="list-item-content">
                                <div class="list-item-title">{{ $artwork->title ?? 'Untitled' }}</div>
                                <div class="list-item-subtitle">by {{ $artwork->artist->name ?? 'Unknown' }}</div>
                            </div>
                            <div class="list-item-meta">
                                <span class="badge badge-{{ $artwork->status === 'approved' ? 'success' : ($artwork->status === 'sold' ? 'info' : 'warning') }}">
                                    {{ ucfirst($artwork->status ?? 'pending') }}
                                </span>
                                <div class="list-item-time">${{ number_format($artwork->price ?? 0, 2) }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-palette"></i>
                            <p>No recent artworks</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const salesChartData = @json($salesChartData ?? []);
    
    if (salesChartData.length > 0) {
        const ctx = document.getElementById('salesChart');
        
        if (ctx) {
            const labels = salesChartData.map(data => data.month);
            const salesData = salesChartData.map(data => data.sales);
            const transactionData = salesChartData.map(data => data.transactions);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Revenue ($)',
                            data: salesData,
                            borderColor: '#408dfb',
                            backgroundColor: 'rgba(64, 141, 251, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Transactions',
                            data: transactionData,
                            borderColor: '#22b573',
                            backgroundColor: 'rgba(34, 181, 115, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(33, 37, 41, 0.95)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            cornerRadius: 4,
                            displayColors: true
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6c757d',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: {
                                color: '#e8e8e8'
                            },
                            ticks: {
                                color: '#6c757d',
                                font: {
                                    size: 11
                                },
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                color: '#6c757d',
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }
    }
});
</script>
@endpush
