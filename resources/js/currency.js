/**
 * Currency Switching Functionality
 */

class CurrencyManager {
    constructor() {
        this.currentCurrency = this.getCurrentCurrency();
        this.init();
    }

    init() {
        // Setup event listeners
        this.setupEventListeners();
    }

    getCurrentCurrency() {
        return document.querySelector('.currency-display')?.textContent.trim() || 'USD';
    }

    setupEventListeners() {
        // Listen for currency changes
        document.addEventListener('currencyChanged', (event) => {
            this.currentCurrency = event.detail.currency;
            this.updateCurrencyDisplay();
        });
    }

    updateCurrencyDisplay() {
        const displays = document.querySelectorAll('.currency-display');
        displays.forEach(display => {
            display.textContent = this.currentCurrency;
        });
    }

    async switchCurrency(newCurrency) {
        if (newCurrency === this.currentCurrency) {
            return;
        }

        if (typeof window.showToast === 'function') {
            window.showToast('Switching currency...', 'info');
        }

        const baseUrl = document.documentElement.getAttribute('data-base-url') || document.body.getAttribute('data-base-url') || '';
        const cleanBaseUrl = baseUrl.endsWith('/') ? baseUrl.slice(0, -1) : baseUrl;

        try {
            const response = await fetch(`${cleanBaseUrl}/currency/switch`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    currency: newCurrency
                })
            });

            const data = await response.json();

            if (data.success) {
                // Update current currency
                this.currentCurrency = data.currency;
                
                // Dispatch custom event
                document.dispatchEvent(new CustomEvent('currencyChanged', {
                    detail: {
                        currency: data.currency,
                        symbol: data.symbol,
                        name: data.name
                    }
                }));

                // Show success message
                if (typeof window.showToast === 'function') {
                    window.showToast(`Currency switched to ${data.name} (${data.currency})`, 'success');
                }
                
                // Reload page to update all server-side rendered content
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                if (typeof window.showToast === 'function') {
                    window.showToast(data.message || 'Failed to switch currency', 'error');
                }
            }
        } catch (error) {
            console.error('Currency switch error:', error);
            if (typeof window.showToast === 'function') {
                window.showToast('Error switching currency', 'error');
            }
        }
    }
}

// Instantiate and attach to window
const currencyManager = new CurrencyManager();
window.currencyManager = currencyManager;
window.switchCurrency = (currency) => currencyManager.switchCurrency(currency);
