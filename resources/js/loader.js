/**
 * Professional Enterprise Loader
 * Handles page loading, AJAX requests, and navigation loading states
 */

class PageLoader {
    constructor() {
        this.loader = document.getElementById('global-loader');
        this.progressBar = document.getElementById('loader-progress');
        this.fadeOverlay = document.getElementById('loader-fade');
        this.minimumLoadTime = 800; // Minimum time to show loader
        this.startTime = 0;
        this.loadTimeout = null;
        this.isLoaded = false;
        
        this.init();
    }
    
    init() {
        this.startTime = Date.now();
        this.simulateProgress();
        this.hideOnLoad();
        
        // Handle page visibility changes
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                this.hide();
            }
        });
        
        // Add loader styles to head
        this.injectStyles();
    }
    
    injectStyles() {
        // Styles are now loaded via app.blade.php for better performance and reliability
    }
    
    simulateProgress() {
        let progress = 0;
        let lastUpdate = Date.now();
        
        const increment = () => {
            if (this.isLoaded) return;
            
            const now = Date.now();
            const deltaTime = now - lastUpdate;
            lastUpdate = now;
            
            // Simulate realistic loading progress with variable speed
            let incrementRate;
            if (progress < 30) {
                incrementRate = Math.random() * 20 + 10; // Fast start
            } else if (progress < 70) {
                incrementRate = Math.random() * 10 + 5;  // Medium speed
            } else {
                incrementRate = Math.random() * 5 + 2;   // Slow finish
            }
            
            progress = Math.min(progress + incrementRate, 95);
            
            if (this.progressBar) {
                // Use requestAnimationFrame for smoother animations
                requestAnimationFrame(() => {
                    this.progressBar.style.width = progress + '%';
                });
            }
            
            if (progress < 95) {
                // Variable timing for more realistic feel
                const delay = Math.max(50, 150 - progress);
                this.loadTimeout = setTimeout(increment, delay);
            }
        };
        
        // Start after a short delay for initial render
        setTimeout(increment, 100);
    }
    
    hideOnLoad() {
        // Wait for DOM content loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.scheduleHide());
        } else {
            this.scheduleHide();
        }
        
        // Also wait for window load (images, stylesheets, etc.)
        window.addEventListener('load', () => this.scheduleHide());
    }
    
    scheduleHide() {
        const elapsed = Date.now() - this.startTime;
        const remaining = Math.max(0, this.minimumLoadTime - elapsed);
        
        setTimeout(() => this.hide(), remaining);
    }
    
    show() {
        if (!this.loader) return;
        
        this.isLoaded = false;
        this.loader.classList.remove('hidden');
        this.fadeOverlay.classList.remove('fade-out');
        
        // Reset progress
        if (this.progressBar) {
            this.progressBar.style.width = '0%';
        }
        
        this.startTime = Date.now();
        this.simulateProgress();
        
        // Prevent scrolling
        document.body.style.overflow = 'hidden';
    }
    
    hide() {
        if (!this.loader || this.isLoaded) return;
        
        this.isLoaded = true;
        
        // Clear any pending progress updates
        if (this.loadTimeout) {
            clearTimeout(this.loadTimeout);
        }
        
        // Complete progress bar with smooth animation
        if (this.progressBar) {
            requestAnimationFrame(() => {
                this.progressBar.style.transition = 'width 0.3s ease-out';
                this.progressBar.style.width = '100%';
            });
        }
        
        // Fade out overlay and loader together
        requestAnimationFrame(() => {
            if (this.fadeOverlay) {
                this.fadeOverlay.style.transition = 'opacity 0.4s ease-out';
                this.fadeOverlay.style.opacity = '0';
            }
            
            this.loader.style.transition = 'opacity 0.4s ease-out';
            this.loader.style.opacity = '0';
        });
        
        // Hide loader after transition
        setTimeout(() => {
            this.loader.classList.add('hidden');
            document.body.style.overflow = '';
            
            // Trigger page content fade in
            this.fadeInContent();
        }, 400);
    }
    
    fadeInContent() {
        const mainContent = document.querySelector('main');
        if (mainContent) {
            mainContent.classList.add('page-content');
            setTimeout(() => {
                mainContent.classList.add('loaded');
            }, 50);
        }
    }
    
    // Mini loader for AJAX requests
    showMiniLoader(container) {
        const miniLoader = document.createElement('div');
        miniLoader.className = 'mini-loader';
        miniLoader.style.color = '#1a1a1a';
        container.appendChild(miniLoader);
        return miniLoader;
    }
    
    removeMiniLoader(miniLoader) {
        if (miniLoader && miniLoader.parentNode) {
            miniLoader.parentNode.removeChild(miniLoader);
        }
    }
    
    // Button loader
    showButtonLoader(button, originalText) {
        button.classList.add('btn-loader');
        button.dataset.originalText = originalText;
        button.disabled = true;
    }
    
    hideButtonLoader(button) {
        button.classList.remove('btn-loader');
        button.disabled = false;
        if (button.dataset.originalText) {
            button.textContent = button.dataset.originalText;
            delete button.dataset.originalText;
        }
    }
}

// Global loader instance
let pageLoader;

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        pageLoader = new PageLoader();
    });
} else {
    pageLoader = new PageLoader();
}

// Export for use in other scripts
window.PageLoader = PageLoader;
window.pageLoader = pageLoader;

// AJAX Interceptor for fetch
const originalFetch = window.fetch;
window.fetch = function(...args) {
    const url = args[0];
    const options = args[1] || {};
    
    // Don't show loader for certain requests
    const skipLoader = options.skipLoader || 
                       url.includes('/cart/count') ||
                       url.includes('/api/') && !url.includes('/checkout/');
    
    let miniLoaderElement = null;
    
    if (!skipLoader && options.showLoader) {
        // Show mini loader in a container if specified
        const container = options.loaderContainer;
        if (container && pageLoader) {
            miniLoaderElement = pageLoader.showMiniLoader(container);
        }
    }
    
    return originalFetch.apply(this, args).finally(() => {
        // Hide mini loader if shown
        if (miniLoaderElement && pageLoader) {
            pageLoader.removeMiniLoader(miniLoaderElement);
        }
    });
};

// Navigation loader
document.addEventListener('click', (e) => {
    const link = e.target.closest('a');
    
    // Only for internal links that aren't already loading
    if (link && 
        link.href && 
        link.hostname === window.location.hostname &&
        !link.hasAttribute('data-no-loader') &&
        !link.hasAttribute('target') &&
        !link.href.includes('#') &&
        !link.classList.contains('no-loader')) {
        
        // Show loader before navigation
        // Uncomment if you want loader on every navigation
        // pageLoader.show();
    }
});

// Form submission loader
document.addEventListener('submit', (e) => {
    const form = e.target;
    const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
    
    if (form && !form.hasAttribute('data-no-loader') && submitButton) {
        // Show button loader for form submissions
        if (pageLoader) {
            const originalText = submitButton.textContent || submitButton.value;
            pageLoader.showButtonLoader(submitButton, originalText);
        }
    }
});

// Utility functions for specific loading scenarios
window.showLoader = () => pageLoader.show();
window.hideLoader = () => pageLoader.hide();
