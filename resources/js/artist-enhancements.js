// Enhanced Artist Page JavaScript

class ArtistPageEnhancer {
    constructor() {
        this.artistId = null;
        this.artistSlug = null;
        this.currentFilter = 'all';
        this.currentSort = 'latest';
        this.currentPage = 1;
        this.isLoading = false;
        this.hasMore = true;
        this.init();
    }

    init() {
        this.getArtistInfo();
        this.bindEvents();
        this.initializeAnimations();
        this.setupIntersectionObserver();
        this.enhanceAccessibility();
    }

    getArtistInfo() {
        const artworksGrid = document.querySelector('#artworks-grid');
        if (artworksGrid) {
            this.artistSlug = artworksGrid.dataset.artistSlug;
            this.currentPage = parseInt(artworksGrid.dataset.currentPage) || 1;
            this.hasMore = artworksGrid.dataset.hasMore === 'true';
        }
    }

    bindEvents() {
        // Enhanced tab switching
        document.querySelectorAll('[data-tab]').forEach(button => {
            button.addEventListener('click', (e) => this.switchTab(e.target.dataset.tab));
        });

        // Filter buttons
        document.querySelectorAll('[data-filter]').forEach(button => {
            button.addEventListener('click', (e) => this.filterArtworks(e.target.dataset.filter));
        });

        // Sort dropdown
        const sortSelect = document.querySelector('#sort-select');
        if (sortSelect) {
            sortSelect.addEventListener('change', (e) => this.sortArtworks(e.target.value));
        }

        // Follow button enhancement
        const followBtn = document.querySelector('#follow-btn');
        if (followBtn) {
            followBtn.addEventListener('click', () => this.toggleFollow());
        }

        // Share functionality
        const shareBtn = document.querySelector('#share-btn');
        if (shareBtn) {
            shareBtn.addEventListener('click', () => this.shareProfile());
        }

        // QR Code download
        const qrBtn = document.querySelector('#download-qr-btn');
        if (qrBtn) {
            qrBtn.addEventListener('click', () => this.downloadQRCode());
        }

        // Subscribe button
        const subscribeBtn = document.querySelector('#subscribe-btn');
        if (subscribeBtn) {
            subscribeBtn.addEventListener('click', () => this.subscribeToArtist());
        }

        // Load more button
        const loadMoreBtn = document.querySelector('#load-more-btn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => this.loadMoreArtworks());
        }
    }

    switchTab(tabName) {
        // Hide all content with fade effect
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
            content.style.opacity = '0';
        });
        
        // Reset all tabs to inactive state
        document.querySelectorAll('[data-tab]').forEach(tab => {
            tab.classList.remove('tab-active', 'bg-white', 'text-gray-900', 'shadow-sm');
            tab.classList.add('text-gray-600', 'hover:bg-white/50');
        });
        
        // Show selected content with fade in
        const selectedContent = document.getElementById(tabName + '-content');
        if (selectedContent) {
            selectedContent.classList.remove('hidden');
            setTimeout(() => {
                selectedContent.style.opacity = '1';
                selectedContent.style.transition = 'opacity 0.3s ease';
            }, 10);
        }
        
        // Activate selected tab
        const activeTab = document.querySelector(`[data-tab="${tabName}"]`);
        if (activeTab) {
            activeTab.classList.remove('text-gray-600', 'hover:bg-white/50');
            activeTab.classList.add('tab-active');
        }
    }

    filterArtworks(filter) {
        this.currentFilter = filter;
        
        // Update active filter button
        document.querySelectorAll('[data-filter]').forEach(btn => {
            btn.classList.remove('bg-purple-600', 'text-white', 'active');
            btn.classList.add('bg-white', 'text-gray-700');
        });
        
        const activeBtn = document.querySelector(`[data-filter="${filter}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('bg-white', 'text-gray-700');
            activeBtn.classList.add('bg-purple-600', 'text-white', 'active');
        }
        
        // Apply filter to artworks
        this.applyFiltersAndSort();
        this.showToast(`Filtering artworks: ${filter}`, 'info');
    }

    sortArtworks(sortBy) {
        this.currentSort = sortBy;
        this.applyFiltersAndSort();
        this.showToast(`Sorting artworks: ${sortBy}`, 'info');
    }

    applyFiltersAndSort() {
        const artworksGrid = document.querySelector('#artworks-grid');
        if (!artworksGrid) return;

        const artworks = Array.from(artworksGrid.children);
        
        // Filter artworks
        let filteredArtworks = artworks.filter(artwork => {
            if (this.currentFilter === 'all') return true;
            if (this.currentFilter === 'available') {
                return !artwork.dataset.sold;
            }
            if (this.currentFilter === 'sold') {
                return artwork.dataset.sold === 'true';
            }
            return true;
        });

        // Sort artworks
        filteredArtworks.sort((a, b) => {
            switch (this.currentSort) {
                case 'price-low':
                    return (parseFloat(a.dataset.price) || 0) - (parseFloat(b.dataset.price) || 0);
                case 'price-high':
                    return (parseFloat(b.dataset.price) || 0) - (parseFloat(a.dataset.price) || 0);
                case 'popular':
                    return (parseInt(b.dataset.views) || 0) - (parseInt(a.dataset.views) || 0);
                case 'latest':
                default:
                    return (parseInt(b.dataset.date) || 0) - (parseInt(a.dataset.date) || 0);
            }
        });

        // Re-render artworks with animation
        artworksGrid.style.opacity = '0';
        setTimeout(() => {
            artworksGrid.innerHTML = '';
            filteredArtworks.forEach((artwork, index) => {
                artwork.style.opacity = '0';
                artwork.style.transform = 'translateY(20px)';
                artworksGrid.appendChild(artwork);
                
                setTimeout(() => {
                    artwork.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    artwork.style.opacity = '1';
                    artwork.style.transform = 'translateY(0)';
                }, index * 100);
            });
            artworksGrid.style.opacity = '1';
        }, 300);
    }

    async toggleFollow() {
        const button = event.target.closest('button');
        if (!button) return;

        const artistId = button.dataset.artistId;
        const originalContent = button.innerHTML;
        
        // Show loading state
        button.innerHTML = this.getLoadingSpinner();
        button.disabled = true;

        const baseUrl = window.baseUrl || document.documentElement.getAttribute('data-base-url') || document.body.getAttribute('data-base-url') || '';
        const cleanBaseUrl = baseUrl.endsWith('/') ? baseUrl.slice(0, -1) : baseUrl;

        try {
            const response = await fetch(`${cleanBaseUrl}/artists/${artistId}/follow`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success) {
                this.showToast(data.message, 'success');
                button.innerHTML = data.following 
                    ? this.getFollowingIcon() + ' Following'
                    : this.getFollowIcon() + ' Follow Artist';
                
                // Add animation
                button.classList.add('animate-pulse');
                setTimeout(() => button.classList.remove('animate-pulse'), 1000);
                
                // Update follower count
                this.updateFollowerCount(data.following ? 1 : -1);
            } else {
                throw new Error(data.message || 'Error updating follow status');
            }
        } catch (error) {
            console.error('Error toggling follow:', error);
            this.showToast(error.message || 'Error updating follow status', 'error');
            button.innerHTML = originalContent;
        } finally {
            button.disabled = false;
        }
    }

    shareProfile() {
        const url = window.location.href;
        const title = document.title;
        
        if (navigator.share) {
            navigator.share({
                title: title,
                url: url,
                text: `Check out ${this.getArtistName()} on Panchi Gallery`
            }).catch(err => console.log('Error sharing:', err));
        } else {
            // Fallback: copy to clipboard
            navigator.clipboard.writeText(url).then(() => {
                this.showToast('Profile link copied to clipboard!', 'success');
            }).catch(err => {
                console.error('Error copying to clipboard:', err);
                this.showToast('Unable to copy link', 'error');
            });
        }
    }

    downloadQRCode() {
        const qrUrl = document.querySelector('#qr-code-img')?.src;
        if (!qrUrl) {
            this.showToast('QR Code not available', 'error');
            return;
        }

        const link = document.createElement('a');
        link.href = qrUrl;
        link.download = `${this.getArtistSlug()}-qrcode.png`;
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        this.showToast('QR Code downloaded!', 'success');
    }

    subscribeToArtist() {
        this.showToast('Subscribing to artist updates...', 'info');
        
        // Simulate subscription API call
        setTimeout(() => {
            this.showToast(`Successfully subscribed to ${this.getArtistName()}'s updates!`, 'success');
        }, 1500);
    }

    async loadMoreArtworks() {
        const button = event.target.closest('button');
        if (!button || this.isLoading || !this.hasMore) return;

        this.isLoading = true;
        const originalContent = button.innerHTML;
        button.innerHTML = this.getLoadingSpinner() + ' Loading...';
        button.disabled = true;

        try {
            const nextPage = this.currentPage + 1;
            const baseUrl = window.baseUrl || document.documentElement.getAttribute('data-base-url') || document.body.getAttribute('data-base-url') || '';
            const cleanBaseUrl = baseUrl.endsWith('/') ? baseUrl.slice(0, -1) : baseUrl;

            const response = await fetch(`${cleanBaseUrl}/api/artists/${this.artistSlug}/artworks?page=${nextPage}&filter=${this.currentFilter}&sort=${this.currentSort}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load artworks');
            }

            const data = await response.json();

            if (data.html) {
                const artworksGrid = document.querySelector('#artworks-grid');
                if (artworksGrid) {
                    // Create a temp container to parse HTML
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.html;
                    
                    // Add animation to new cards
                    const newCards = Array.from(tempDiv.children);
                    newCards.forEach((card, index) => {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        artworksGrid.appendChild(card);
                        
                        setTimeout(() => {
                            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, index * 100);
                    });
                }
            }

            // Update state
            this.currentPage = data.current_page;
            this.hasMore = data.has_more;

            // Hide button if no more artworks
            if (!this.hasMore) {
                button.style.display = 'none';
                this.showToast('All artworks loaded!', 'success');
            } else {
                button.innerHTML = originalContent;
                this.showToast('More artworks loaded!', 'success');
            }

        } catch (error) {
            console.error('Error loading more artworks:', error);
            this.showToast('Error loading more artworks', 'error');
            button.innerHTML = originalContent;
        } finally {
            this.isLoading = false;
            button.disabled = false;
        }
    }

    updateFollowerCount(change) {
        const countElement = document.querySelector('#followers-count');
        if (countElement) {
            const currentCount = parseInt(countElement.textContent) || 0;
            countElement.textContent = currentCount + change;
        }
    }

    initializeAnimations() {
        // Add entrance animations to elements
        const elements = document.querySelectorAll('.artwork-card, .stat-card, .card-luxury');
        elements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            setTimeout(() => {
                el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    setupIntersectionObserver() {
        // Lazy loading and scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.artwork-card').forEach(card => {
            observer.observe(card);
        });
    }

    enhanceAccessibility() {
        // Add ARIA labels and keyboard navigation
        document.querySelectorAll('button, a').forEach(element => {
            if (!element.getAttribute('aria-label')) {
                element.setAttribute('aria-label', element.textContent.trim());
            }
        });

        // Add keyboard navigation for tabs
        document.querySelectorAll('[data-tab]').forEach((tab, index) => {
            tab.setAttribute('role', 'tab');
            tab.setAttribute('tabindex', index === 0 ? '0' : '-1');
            
            tab.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
                    e.preventDefault();
                    const tabs = Array.from(document.querySelectorAll('[data-tab]'));
                    const currentIndex = tabs.indexOf(tab);
                    const nextIndex = e.key === 'ArrowRight' 
                        ? (currentIndex + 1) % tabs.length 
                        : (currentIndex - 1 + tabs.length) % tabs.length;
                    tabs[nextIndex].focus();
                    tabs[nextIndex].click();
                }
            });
        });
    }

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500',
            warning: 'bg-yellow-500'
        };
        
        toast.className = `fixed bottom-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-y-full opacity-0`;
        toast.textContent = message;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'polite');
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-y-full', 'opacity-0');
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.add('translate-y-full', 'opacity-0');
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    // Helper methods
    getArtistName() {
        return document.querySelector('#artist-name')?.textContent || 'Artist';
    }

    getArtistSlug() {
        return document.querySelector('#artist-slug')?.textContent || 'artist';
    }

    getLoadingSpinner() {
        return '<svg class="animate-spin h-5 w-5 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    }

    getFollowIcon() {
        return '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>';
    }

    getFollowingIcon() {
        return '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>';
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.artistPageEnhancer = new ArtistPageEnhancer();
});

// Export for potential use in other scripts
window.ArtistPageEnhancer = ArtistPageEnhancer;

// Global functions for onclick handlers
function loadMoreArtworks() {
    if (window.artistPageEnhancer) {
        window.artistPageEnhancer.loadMoreArtworks();
    }
}

function switchTab(tabName) {
    if (window.artistPageEnhancer) {
        window.artistPageEnhancer.switchTab(tabName);
    }
}

function filterArtworks(filter) {
    if (window.artistPageEnhancer) {
        window.artistPageEnhancer.filterArtworks(filter);
    }
}

function sortArtworks(sortBy) {
    if (window.artistPageEnhancer) {
        window.artistPageEnhancer.sortArtworks(sortBy);
    }
}

function toggleFollow(artistId) {
    if (window.artistPageEnhancer) {
        window.artistPageEnhancer.toggleFollow();
    }
}

function shareArtistProfile() {
    if (window.artistPageEnhancer) {
        window.artistPageEnhancer.shareProfile();
    }
}

function downloadQRCode() {
    if (window.artistPageEnhancer) {
        window.artistPageEnhancer.downloadQRCode();
    }
}

function subscribeToArtist() {
    if (window.artistPageEnhancer) {
        window.artistPageEnhancer.subscribeToArtist();
    }
}
