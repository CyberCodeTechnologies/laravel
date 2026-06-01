@php
    $adminUrl = $adminUrl ?? fn (string $name, array $params = []) => route($name, $params);
    $active = function (...$patterns) {
        foreach ($patterns as $pattern) {
            if (request()->routeIs($pattern)) {
                return true;
            }
        }
        return false;
    };

    $nav = [
        ['section' => null, 'items' => [
            ['url' => $adminUrl('admin.dashboard'), 'active' => $active('admin.dashboard*'), 'icon' => 'home', 'label' => __('messages.admin_menu.home')],
        ]],
        ['section' => __('messages.admin_menu.members'), 'items' => [
            ['url' => $adminUrl('admin.users'), 'active' => $active('admin.users*'), 'icon' => 'customers', 'label' => __('messages.admin_menu.users')],
            ['url' => $adminUrl('admin.artists'), 'active' => $active('admin.artists*', 'admin.pending-artists*'), 'icon' => 'customers', 'label' => __('messages.admin_menu.reviewers')],
        ]],
        ['section' => __('messages.admin_menu.items'), 'items' => [
            ['url' => $adminUrl('admin.artworks'), 'active' => $active('admin.artworks*'), 'icon' => 'items', 'label' => __('messages.admin_menu.artworks')],
            ['url' => $adminUrl('admin.collections'), 'active' => $active('admin.collections*'), 'icon' => 'items', 'label' => __('messages.admin_menu.collections')],
        ]],
        ['section' => __('messages.admin_menu.finance'), 'items' => [
            ['url' => $adminUrl('admin.transactions'), 'active' => $active('admin.transactions*'), 'icon' => 'banking', 'label' => __('messages.admin_menu.transactions')],
            ['url' => $adminUrl('admin.payouts.index'), 'active' => $active('admin.payouts*', 'admin.revenue'), 'icon' => 'purchases', 'label' => __('messages.admin_menu.withdrawals')],
            ['url' => $adminUrl('admin.payments.verifications'), 'active' => $active('admin.payments*'), 'icon' => 'documents', 'label' => __('messages.admin_menu.payment_verification')],
        ]],
        ['section' => __('messages.admin_menu.marketplace'), 'items' => [
            ['url' => $adminUrl('admin.marketplace'), 'active' => $active('admin.marketplace*', 'admin.resales*'), 'icon' => 'sales', 'label' => __('messages.admin_menu.marketplace')],
        ]],
        ['section' => __('messages.admin_menu.catalog'), 'items' => [
            ['url' => $adminUrl('admin.categories'), 'active' => $active('admin.categories*'), 'icon' => 'items', 'label' => __('messages.admin_menu.categories')],
        ]],
        ['section' => __('messages.admin_menu.support'), 'items' => [
            ['url' => $adminUrl('admin.support'), 'active' => $active('admin.support', 'admin.support.faq*'), 'icon' => 'documents', 'label' => __('messages.admin_menu.tickets')],
            ['url' => $adminUrl('admin.support.contacts'), 'active' => $active('admin.support.contacts*'), 'icon' => 'documents', 'label' => __('messages.admin_menu.contacts')],
        ]],
        ['section' => __('messages.admin_menu.cms'), 'items' => [
            ['url' => $adminUrl('admin.exhibitions.index'), 'active' => $active('admin.exhibitions*'), 'icon' => 'items', 'label' => __('messages.admin_menu.exhibitions')],
            ['url' => $adminUrl('admin.blogs.index'), 'active' => $active('admin.blogs*'), 'icon' => 'documents', 'label' => __('messages.admin_menu.blog')],
            ['url' => $adminUrl('admin.page-contents.index'), 'active' => $active('admin.page-contents*'), 'icon' => 'documents', 'label' => __('messages.admin_menu.page_content')],
            ['url' => $adminUrl('admin.frontend.dashboard'), 'active' => $active('admin.frontend*'), 'icon' => 'documents', 'label' => __('messages.admin_menu.frontend')],
        ]],
        ['section' => __('messages.admin_menu.analytics'), 'items' => [
            ['url' => $adminUrl('admin.reports.dashboard'), 'active' => $active('admin.reports*'), 'icon' => 'reports', 'label' => __('messages.admin_menu.reports')],
        ]],
    ];
@endphp
<aside class="zoho-sidebar">
    <div class="zoho-sidebar-brand">
        <div class="brand-logo">PG</div>
        <span class="brand-name">Panchi Gallery</span>
    </div>
    <nav class="zoho-sidebar-nav">
        @foreach ($nav as $group)
            @if ($group['section'])
                <div class="nav-section-title">{{ $group['section'] }}</div>
            @endif
            @foreach ($group['items'] as $item)
                <a href="{{ $item['url'] }}"
                   class="zoho-nav-item {{ $item['active'] ? 'active' : '' }}">
                    @include('admin.partials.icons', ['icon' => $item['icon']])
                    <span class="nav-label">{{ $item['label'] }}</span>
                </a>
            @endforeach
        @endforeach
    </nav>
    <div class="zoho-sidebar-footer">
        <a href="{{ $adminUrl('admin.settings') }}"
           class="zoho-nav-item {{ $active('admin.settings*') ? 'active' : '' }}">
            @include('admin.partials.icons', ['icon' => 'settings'])
            <span class="nav-label">{{ __('messages.admin_menu.settings') }}</span>
        </a>
    </div>
</aside>
