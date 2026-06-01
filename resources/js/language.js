/**
 * Language Switching Functionality
 */

/**
 * Switch application language
 * @param {string} language - Language code ('en' or 'my')
 */
export function switchLanguage(language) {
    if (!window.csrfToken) {
        window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    if (!window.csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    // Show loading state if showToast is available
    if (typeof window.showToast === 'function') {
        window.showToast('Switching language...', 'info');
    }

    const baseUrl = document.documentElement.getAttribute('data-base-url') || document.body.getAttribute('data-base-url') || '';
    const cleanBaseUrl = baseUrl.endsWith('/') ? baseUrl.slice(0, -1) : baseUrl;

    fetch(`${cleanBaseUrl}/language/switch`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: JSON.stringify({
            language: language
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update session storage for immediate effect
            sessionStorage.setItem('current_language', data.language);
            if (data.currency) {
                sessionStorage.setItem('current_currency', data.currency);
            }
            
            // Reload the page to apply the new language
            window.location.reload();
        } else {
            if (typeof window.showToast === 'function') {
                window.showToast('Failed to switch language', 'error');
            }
            console.error('Failed to switch language');
        }
    })
    .catch(error => {
        console.error('Error switching language:', error);
        if (typeof window.showToast === 'function') {
            window.showToast('Error switching language', 'error');
        }
    });
}

/**
 * Switch language with fallback mechanism
 * @param {string} lang - Language code ('en' or 'my')
 */
export function switchLanguageWithFallback(lang) {
    console.log('Attempting language switch with fallback to:', lang);
    
    // Try AJAX first
    try {
        switchLanguage(lang);
    } catch (error) {
        console.error('AJAX language switch failed, using fallback:', error);
        // Fallback to form submission
        fallbackLanguageSwitch(lang);
    }
}

/**
 * Fallback language switch using form submission
 * @param {string} lang - Language code ('en' or 'my')
 */
export function fallbackLanguageSwitch(lang) {
    console.log('Using fallback language switch to:', lang);
    
    const form = document.getElementById('language-form');
    const input = document.getElementById('language-input');
    
    if (form && input) {
        input.value = lang;
        form.submit();
    } else {
        console.error('Fallback form not found');
        // Last resort - direct URL with query parameter
        window.location.href = (window.baseUrl || '') + '?lang=' + lang;
    }
}

// Attach to window for global access
window.switchLanguage = switchLanguage;
window.switchLanguageWithFallback = switchLanguageWithFallback;
window.fallbackLanguageSwitch = fallbackLanguageSwitch;
