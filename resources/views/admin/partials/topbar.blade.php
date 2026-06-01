@php
    $adminUrl = $adminUrl ?? fn (string $name, array $params = []) => route($name, $params);
    $user = auth()->user();
    $initials = strtoupper(substr($user->name ?? 'A', 0, 2));
@endphp
<header class="zoho-topbar">
    <button type="button" class="zoho-topbar-toggle" data-zoho-sidebar-toggle aria-label="Toggle sidebar">
        @include('admin.partials.icons', ['icon' => 'menu'])
    </button>
    <span class="zoho-topbar-module">{{ $moduleTitle ?? 'Home' }}</span>

    <div class="zoho-topbar-search">
        <span class="search-icon">@include('admin.partials.icons', ['icon' => 'search'])</span>
        <input type="search"
               placeholder="Search in Panchi Gallery (/)"
               data-zoho-search
               data-search-url="{{ $adminUrl('admin.users') }}"
               aria-label="Search">
    </div>

    <div class="zoho-topbar-actions">
        <div class="zoho-dropdown" data-zoho-dropdown>
            <button type="button" class="zoho-topbar-btn org-btn" data-zoho-dropdown-trigger>
                <span class="org-name">Panchi Gallery</span>
                @include('admin.partials.icons', ['icon' => 'chevron-down'])
            </button>
            <div class="zoho-dropdown-menu">
                <div class="zoho-dropdown-header">Organisation</div>
                <span class="zoho-dropdown-item" style="cursor:default;">Panchi Gallery</span>
            </div>
        </div>

        <div class="zoho-dropdown" data-zoho-dropdown>
            <button type="button" class="zoho-topbar-btn quick-create" data-zoho-dropdown-trigger aria-label="Quick Create" title="Quick Create">
                @include('admin.partials.icons', ['icon' => 'plus'])
            </button>
            <div class="zoho-dropdown-menu">
                <div class="zoho-dropdown-header">Quick Create</div>
                <a href="{{ route('admin.users.create') }}" class="zoho-dropdown-item">New User</a>
                <a href="{{ route('admin.artworks.create') }}" class="zoho-dropdown-item">New Artwork</a>
                <a href="{{ route('admin.categories.create') }}" class="zoho-dropdown-item">New Category</a>
                <a href="{{ route('admin.collections.create') }}" class="zoho-dropdown-item">New Collection</a>
            </div>
        </div>

        <div class="zoho-dropdown" data-zoho-dropdown>
            <button type="button" class="zoho-topbar-btn icon-only" data-zoho-dropdown-trigger aria-label="Notifications" title="Notifications">
                @include('admin.partials.icons', ['icon' => 'bell'])
                <span class="notif-dot"></span>
            </button>
            <div class="zoho-dropdown-menu" style="min-width:320px;">
                <div class="zoho-dropdown-header">Notifications</div>
                <a href="{{ route('admin.users.pending') }}" class="zoho-dropdown-item">Pending user approvals</a>
                <a href="{{ route('admin.artworks.pending') }}" class="zoho-dropdown-item">Pending artworks</a>
                <a href="{{ route('admin.pending-artists') }}" class="zoho-dropdown-item">Pending artists</a>
                <div class="zoho-dropdown-divider"></div>
                <a href="{{ route('admin.support.contacts') }}" class="zoho-dropdown-item">Support contacts</a>
            </div>
        </div>

        <a href="{{ $adminUrl('admin.settings') }}" class="zoho-topbar-btn icon-only" aria-label="Settings" title="Settings">
            @include('admin.partials.icons', ['icon' => 'gear'])
        </a>

        <div class="zoho-dropdown" data-zoho-dropdown>
            <button type="button" class="zoho-topbar-btn" data-zoho-dropdown-trigger aria-label="Profile">
                <span class="zoho-avatar">{{ $initials }}</span>
            </button>
            <div class="zoho-dropdown-menu">
                <span class="zoho-dropdown-item" style="cursor:default;font-weight:600;">{{ $user->name }}</span>
                <span class="zoho-dropdown-item" style="cursor:default;font-size:11px;color:var(--zoho-text-muted);">{{ $user->email }}</span>
                <div class="zoho-dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="zoho-dropdown-item">Sign Out</button>
                </form>
            </div>
        </div>
    </div>
</header>
