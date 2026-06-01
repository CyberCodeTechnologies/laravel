// Import currency functionality
import './currency.js';

// Import language functionality
import './language.js';

// Import artist enhancements
import './artist-enhancements.js';

// Initialize application
document.addEventListener('DOMContentLoaded', () => {
    // Add CSRF token for AJAX requests
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        window.csrfToken = token;
    }
});
