// Artwork Actions - Buy Now and Wishlist functionality

// Ensure window.baseUrl is defined
if (typeof window.baseUrl === 'undefined') {
    const dataBaseUrl = document.documentElement.getAttribute('data-base-url') || document.body.getAttribute('data-base-url');
    window.baseUrl = dataBaseUrl ? (dataBaseUrl.endsWith('/') ? dataBaseUrl.slice(0, -1) : dataBaseUrl) : window.location.origin;
}

// Show toast notification
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) {
        console.warn('Toast container not found');
        return;
    }

    const toast = document.createElement('div');
    const bgColors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500',
        warning: 'bg-yellow-500'
    };

    toast.className = `${bgColors[type] || bgColors.success} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
    toast.textContent = message;

    container.appendChild(toast);

    // Animate in
    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full');
    });

    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

// Add to Cart functionality
function addToCart(artworkId, btn = null) {
    const quantityInput = document.getElementById('quantity');
    const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
    const originalText = btn ? btn.innerHTML : null;
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Adding...';
    }

    const baseUrl = window.baseUrl || window.location.origin;
    const cleanBaseUrl = baseUrl.endsWith('/') ? baseUrl.slice(0, -1) : baseUrl;

    fetch(`${cleanBaseUrl}/cart/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify({
            artwork_id: artworkId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Item added to cart successfully!', 'success');
            updateCartCount();
            if (btn) {
                btn.innerHTML = '<i class="fas fa-check mr-2"></i> Added to Cart';
                btn.classList.remove('btn-luxury');
                btn.classList.add('bg-green-600', 'text-white');
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    btn.classList.remove('bg-green-600', 'text-white');
                    btn.classList.add('btn-luxury');
                }, 2000);
            }
        } else {
            showToast('Failed to add item to cart', 'error');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        showToast('Error adding to cart', 'error');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
}

// Buy Now functionality
function buyNow(artworkId) {
    if (!artworkId) return;

    // Show loading toast
    if (typeof window.showToast === 'function') {
        window.showToast('Redirecting to checkout...', 'info');
    }

    const baseUrl = window.baseUrl || window.location.origin;
    const cleanBaseUrl = baseUrl.endsWith('/') ? baseUrl.slice(0, -1) : baseUrl;

    // Add to cart first, then redirect to checkout
    fetch(`${cleanBaseUrl}/cart/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify({
            artwork_id: artworkId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = `${cleanBaseUrl}/checkout`;
        } else {
            if (typeof window.showToast === 'function') {
                window.showToast(data.message || 'Failed to process request', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error in buy now:', error);
        // If API fails, try direct redirect as fallback (cart might handle it)
        window.location.href = `${cleanBaseUrl}/checkout?artwork_id=${artworkId}`;
    });
}

// Toggle Wishlist functionality
function toggleWishlist(artworkId) {
    const baseUrl = window.baseUrl || window.location.origin;
    const cleanBaseUrl = baseUrl.endsWith('/') ? baseUrl.slice(0, -1) : baseUrl;

    fetch(`${cleanBaseUrl}/wishlist/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify({
            artwork_id: artworkId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update wishlist button state
            updateWishlistButton(artworkId, data.in_wishlist);

            // Update wishlist count in navigation
            updateWishlistCount(data.wishlist_count);

            // Show success message
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Error updating wishlist', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating wishlist', 'error');
    });
}

// Toggle Like functionality
function toggleLike(artworkId) {
    const baseUrl = window.baseUrl || window.location.origin;
    const cleanBaseUrl = baseUrl.endsWith('/') ? baseUrl.slice(0, -1) : baseUrl;

    fetch(`${cleanBaseUrl}/collector/artworks/${artworkId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Like updated', 'success');
            // Update like button state if needed
            updateLikeButton(artworkId, data.liked);
        } else {
            showToast(data.message || 'Error updating like', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating like', 'error');
    });
}

// Update like button state
function updateLikeButton(artworkId, liked) {
    const buttons = document.querySelectorAll(`[onclick*="toggleLike(${artworkId})"]`);
    buttons.forEach(button => {
        const icon = button.querySelector('i');
        if (icon) {
            if (liked) {
                icon.classList.add('text-red-500');
                icon.classList.remove('text-gray-600');
            } else {
                icon.classList.add('text-gray-600');
                icon.classList.remove('text-red-500');
            }
        }
    });
}

// Make Offer functionality
function makeOffer(artworkId) {
    // Placeholder for offer functionality
    showToast('Offer functionality coming soon', 'info');
}

// Update wishlist button state
function updateWishlistButton(artworkId, inWishlist) {
    const buttons = document.querySelectorAll(`[onclick*="toggleWishlist(${artworkId})"]`);
    buttons.forEach(button => {
        const icon = button.querySelector('i');
        if (inWishlist) {
            button.classList.add('text-red-600', 'border-red-300', 'bg-red-50');
            button.classList.remove('text-gray-700');
            if (icon) {
                icon.classList.remove('far');
                icon.classList.add('fas');
            }
        } else {
            button.classList.add('text-gray-700');
            button.classList.remove('text-red-600', 'border-red-300', 'bg-red-50');
            if (icon) {
                icon.classList.remove('fas');
                icon.classList.add('far');
            }
        }
    });
}

// Update wishlist count in navigation
function updateWishlistCount(count) {
    const wishlistBadge = document.getElementById('wishlist-badge');
    if (wishlistBadge) {
        if (count > 0) {
            wishlistBadge.textContent = count;
            wishlistBadge.classList.remove('hidden');
        } else {
            wishlistBadge.classList.add('hidden');
        }
    }
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Update cart badge count in header
function updateCartCount() {
    const baseUrl = window.baseUrl || window.location.origin;
    fetch(`${baseUrl}/cart/count`)
        .then(response => response.json())
        .then(data => {
            const cartBadge = document.getElementById('cart-badge');
            if (cartBadge && data.count !== undefined) {
                cartBadge.textContent = data.count;
                if (data.count > 0) {
                    cartBadge.classList.remove('hidden');
                } else {
                    cartBadge.classList.add('hidden');
                }
            }
        })
        .catch(error => console.error('Error updating cart count:', error));
}

// Attach functions to window for global access
window.addToCart = addToCart;
window.buyNow = buyNow;
window.updateCartCount = updateCartCount;
window.showArtworkModal = showArtworkModal;
window.quickView = quickView;
window.toggleWishlist = toggleWishlist;
window.addToWishlist = toggleWishlist; // Alias for backward compatibility
window.updateWishlistCount = updateWishlistCount;

// Artwork Modal and Quick View functionality
function quickView(artworkId) {
    if (!artworkId || artworkId === 'null') return;

    // Show loading toast
    if (typeof window.showToast === 'function') {
        window.showToast('Loading artwork details...', 'info');
    }

    fetch(`${window.baseUrl}/api/artworks/${artworkId}`)
        .then(response => response.json())
        .then(artwork => {
            const modal = document.getElementById('artwork-quick-view-modal');
            if (!modal) return;

            // Update modal content
            document.getElementById('modal-artwork-display-title').textContent = artwork.title;
            document.getElementById('modal-artwork-artist').textContent = artwork.artist;
            document.getElementById('modal-artwork-price').textContent = artwork.price ? `$${Number(artwork.price).toLocaleString()}` : 'Price on request';
            document.getElementById('modal-artwork-image').src = artwork.image;
            document.getElementById('modal-artwork-image').alt = artwork.title;

            // Medium
            const mediumRow = document.getElementById('modal-artwork-medium-row');
            if (artwork.medium) {
                document.getElementById('modal-artwork-medium').textContent = artwork.medium;
                mediumRow.classList.remove('hidden');
            } else {
                mediumRow.classList.add('hidden');
            }

            // Size
            const sizeRow = document.getElementById('modal-artwork-size-row');
            if (artwork.size) {
                document.getElementById('modal-artwork-size').textContent = artwork.size;
                sizeRow.classList.remove('hidden');
            } else {
                sizeRow.classList.add('hidden');
            }

            // Year
            const yearRow = document.getElementById('modal-artwork-year-row');
            if (artwork.year) {
                document.getElementById('modal-artwork-year').textContent = artwork.year;
                yearRow.classList.remove('hidden');
            } else {
                yearRow.classList.add('hidden');
            }

            // Description
            const descRow = document.getElementById('modal-artwork-description-row');
            if (artwork.description) {
                document.getElementById('modal-artwork-description').textContent = artwork.description;
                descRow.classList.remove('hidden');
            } else {
                descRow.classList.add('hidden');
            }

            // Status Badge
            const statusDiv = document.getElementById('modal-artwork-status');
            statusDiv.innerHTML = '';
            if (artwork.status === 'sold') {
                statusDiv.innerHTML = '<span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">Sold</span>';
            } else if (artwork.status === 'available') {
                statusDiv.innerHTML = '<span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">Available</span>';
            }

            // Update buttons
            const buyBtn = document.getElementById('modal-buy-now-btn');
            buyBtn.onclick = () => window.buyNow(artwork.id);
            buyBtn.disabled = artwork.status === 'sold';
            buyBtn.classList.toggle('opacity-50', artwork.status === 'sold');
            buyBtn.classList.toggle('cursor-not-allowed', artwork.status === 'sold');

            const wishlistBtn = document.getElementById('modal-wishlist-btn');
            wishlistBtn.onclick = () => window.toggleWishlist(artwork.id);

            // Show modal
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        })
        .catch(error => {
            console.error('Error fetching artwork details:', error);
            if (typeof window.showToast === 'function') {
                window.showToast('Failed to load artwork details', 'error');
            }
        });
}

function showArtworkModal(artworkId) {
    quickView(artworkId);
}

// Promo Code functionality
function applyPromoCode() {
    const input = document.getElementById('promo-code');
    if (!input) {
        showToast('Promo code input not found', 'error');
        return;
    }

    const code = input.value.trim();
    if (!code) {
        showToast('Please enter a promo code', 'warning');
        return;
    }

    const baseUrl = window.baseUrl || window.location.origin;
    fetch(`${baseUrl}/cart/promo-code`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify({ code: code })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            // Update cart summary display
            updateCartSummary(data);
            // Disable input and show remove button
            input.disabled = true;
            const applyBtn = document.querySelector('[onclick="applyPromoCode()"]');
            if (applyBtn) {
                applyBtn.innerHTML = '<i class="fas fa-times mr-1"></i> Remove';
                applyBtn.setAttribute('onclick', 'removePromoCode()');
                applyBtn.classList.remove('bg-gray-200', 'text-gray-700');
                applyBtn.classList.add('bg-red-100', 'text-red-600');
            }
        } else {
            showToast(data.message || 'Invalid promo code', 'error');
        }
    })
    .catch(error => {
        console.error('Error applying promo code:', error);
        showToast('Error applying promo code', 'error');
    });
}

function removePromoCode() {
    const baseUrl = window.baseUrl || window.location.origin;
    fetch(`${baseUrl}/cart/promo-code`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            // Reset input and button
            const input = document.getElementById('promo-code');
            if (input) {
                input.value = '';
                input.disabled = false;
            }
            const removeBtn = document.querySelector('[onclick="removePromoCode()"]');
            if (removeBtn) {
                removeBtn.innerHTML = '{{ __("messages.apply") }}';
                removeBtn.setAttribute('onclick', 'applyPromoCode()');
                removeBtn.classList.remove('bg-red-100', 'text-red-600');
                removeBtn.classList.add('bg-gray-200', 'text-gray-700');
            }
            // Update cart summary
            if (data.formatted_total) {
                document.querySelectorAll('.cart-total').forEach(el => {
                    el.textContent = data.formatted_total;
                });
            }
            // Hide discount row
            const discountRow = document.getElementById('discount-row');
            if (discountRow) {
                discountRow.classList.add('hidden');
            }
        } else {
            showToast(data.message || 'Error removing promo code', 'error');
        }
    })
    .catch(error => {
        console.error('Error removing promo code:', error);
        showToast('Error removing promo code', 'error');
    });
}

function updateCartSummary(data) {
    // Update subtotal
    if (data.formatted_subtotal) {
        const subtotalEl = document.getElementById('cart-subtotal');
        if (subtotalEl) {
            subtotalEl.textContent = data.formatted_subtotal;
        }
    }
    // Update discount
    if (data.formatted_discount && data.discount_amount > 0) {
        const discountRow = document.getElementById('discount-row');
        const discountEl = document.getElementById('cart-discount');
        if (discountRow) {
            discountRow.classList.remove('hidden');
        }
        if (discountEl) {
            discountEl.textContent = '-' + data.formatted_discount;
        }
    }
    // Update total
    if (data.formatted_total) {
        document.querySelectorAll('.cart-total').forEach(el => {
            el.textContent = data.formatted_total;
        });
    }
}

// Initialize wishlist and cart states on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check which artworks are in wishlist and update buttons
    const artworkIds = document.querySelectorAll('[data-artwork-id]');
    artworkIds.forEach(element => {
        const artworkId = element.getAttribute('data-artwork-id');
        // You could implement a check here if needed
    });

    // Initialize cart count
    updateCartCount();
});

// Expose functions to global window object for inline onclick handlers
window.addToCart = addToCart;
window.buyNow = buyNow;
window.toggleWishlist = toggleWishlist;
window.toggleLike = toggleLike;
window.showToast = showToast;
window.showNotification = showNotification;
window.updateCartCount = updateCartCount;
window.updateWishlistCount = updateWishlistCount;
window.updateWishlistButton = updateWishlistButton;
window.updateLikeButton = updateLikeButton;
window.applyPromoCode = applyPromoCode;
window.removePromoCode = removePromoCode;
window.updateCartSummary = updateCartSummary;
