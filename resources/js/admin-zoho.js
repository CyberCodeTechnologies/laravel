document.addEventListener('DOMContentLoaded', () => {
    const app = document.querySelector('.zoho-app');
    if (!app) return;

    const sidebarToggle = document.querySelector('[data-zoho-sidebar-toggle]');
    const stored = localStorage.getItem('zoho-sidebar-collapsed');
    if (stored === '1') {
        app.classList.add('sidebar-collapsed');
    }

    sidebarToggle?.addEventListener('click', () => {
        app.classList.toggle('sidebar-collapsed');
        localStorage.setItem(
            'zoho-sidebar-collapsed',
            app.classList.contains('sidebar-collapsed') ? '1' : '0'
        );
    });

    document.querySelectorAll('[data-zoho-dropdown]').forEach((dropdown) => {
        const trigger = dropdown.querySelector('[data-zoho-dropdown-trigger]');
        trigger?.addEventListener('click', (e) => {
            e.stopPropagation();
            const wasOpen = dropdown.classList.contains('open');
            document.querySelectorAll('[data-zoho-dropdown].open').forEach((d) => d.classList.remove('open'));
            if (!wasOpen) {
                dropdown.classList.add('open');
            }
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('[data-zoho-dropdown].open').forEach((d) => d.classList.remove('open'));
    });

    const widgetsToggle = document.querySelector('[data-zoho-widgets-toggle]');
    const widgetsPane = document.querySelector('.zoho-widgets-pane');
    widgetsToggle?.addEventListener('click', () => {
        widgetsPane?.classList.toggle('collapsed');
    });

    const searchInput = document.querySelector('[data-zoho-search]');
    searchInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && searchInput.value.trim()) {
            window.location.href = `${searchInput.dataset.searchUrl || '#'}?q=${encodeURIComponent(searchInput.value.trim())}`;
        }
    });
});
