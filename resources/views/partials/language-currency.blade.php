<!-- Language and Currency Switcher -->
<div class="flex items-center space-x-4">
    <!-- Language Switcher -->
    <div class="relative" x-data="{ open: false }">
        <button 
            @click="open = !open"
            class="flex items-center space-x-2 px-3 py-2 text-sm text-gray-700 hover:text-black transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 8 15.75a2.25 2.25 0 002.213 3.735l.002-.001c.141-.048.281-.095.42-.144A2.25 2.25 0 0014.25 15.75v-.002a2.25 2.25 0 00-2.244-2.244A2.25 2.25 0 009.75 15.75v.002a2.25 2.25 0 002.244 2.244c.14.049.28.096.42.144.002 0 .002 0 .002.001z"></path>
            </svg>
            <span>{{ app()->getLocale() == 'en' ? 'EN' : 'MY' }}</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        
        <div 
            x-show="open" 
            @click.away="open = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
        >
            <div class="py-1">
                <button onclick="switchLanguage('en')" 
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-black transition-colors">
                    <div class="flex items-center space-x-3">
                        <span class="text-lg">🇺🇸</span>
                        <div>
                            <div class="font-medium">English</div>
                            <div class="text-xs text-gray-500">English</div>
                        </div>
                        @if(app()->getLocale() == 'en')
                            <svg class="w-4 h-4 text-green-600 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                </button>
                
                <button onclick="switchLanguage('my')" 
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-black transition-colors">
                    <div class="flex items-center space-x-3">
                        <span class="text-lg">🇲🇲</span>
                        <div>
                            <div class="font-medium">မြန်မာ</div>
                            <div class="text-xs text-gray-500">Myanmar</div>
                        </div>
                        @if(app()->getLocale() == 'my')
                            <svg class="w-4 h-4 text-green-600 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Currency Switcher -->
    <div class="relative" x-data="{ open: false }">
        <button 
            @click="open = !open"
            class="flex items-center space-x-2 px-3 py-2 text-sm text-gray-700 hover:text-black transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('currency', 'USD') }}</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        
        <div 
            x-show="open" 
            @click.away="open = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
        >
            <div class="py-1">
                <button onclick="switchCurrency('USD')" 
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-black transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="text-lg">🇺🇸</span>
                            <div>
                                <div class="font-medium">USD</div>
                                <div class="text-xs text-gray-500">US Dollar</div>
                            </div>
                        </div>
                        @if(session('currency', 'USD') == 'USD')
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                </button>
                
                <button onclick="switchCurrency('MMK')" 
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-black transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="text-lg">🇲🇲</span>
                            <div>
                                <div class="font-medium">MMK</div>
                                <div class="text-xs text-gray-500">Myanmar Kyat</div>
                            </div>
                        </div>
                        @if(session('currency', 'USD') == 'MMK')
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                </button>
                
                <button onclick="switchCurrency('EUR')" 
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-black transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="text-lg">🇪🇺</span>
                            <div>
                                <div class="font-medium">EUR</div>
                                <div class="text-xs text-gray-500">Euro</div>
                            </div>
                        </div>
                        @if(session('currency', 'USD') == 'EUR')
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                </button>
                
                <button onclick="switchCurrency('GBP')" 
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-black transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="text-lg">🇬🇧</span>
                            <div>
                                <div class="font-medium">GBP</div>
                                <div class="text-xs text-gray-500">British Pound</div>
                            </div>
                        </div>
                        @if(session('currency', 'USD') == 'GBP')
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Language and Currency -->
<div class="md:hidden">
    <div class="border-t border-gray-200 pt-4">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Language & Currency</h3>
        
        <!-- Mobile Language -->
        <div class="mb-4">
            <label class="block text-xs text-gray-600 mb-2">Language</label>
            <div class="grid grid-cols-2 gap-2">
                @php $currentLocale = app()->getLocale(); @endphp
                <button onclick="switchLanguage('en')" 
                        class="flex items-center space-x-2 px-3 py-2 border rounded-lg {{ $currentLocale == 'en' ? 'border-black bg-black text-white' : 'border-gray-300 text-gray-700' }}">
                    <span>🇺🇸</span>
                    <span class="text-sm">English</span>
                </button>
                <button onclick="switchLanguage('my')" 
                        class="flex items-center space-x-2 px-3 py-2 border rounded-lg {{ $currentLocale == 'my' ? 'border-black bg-black text-white' : 'border-gray-300 text-gray-700' }}">
                    <span>🇲🇲</span>
                    <span class="text-sm">မြန်မာ</span>
                </button>
            </div>
        </div>
        
        <!-- Mobile Currency -->
        <div>
            <label class="block text-xs text-gray-600 mb-2">Currency</label>
            <div class="grid grid-cols-2 gap-2">
                @php $currentCurrency = session('currency', 'USD'); @endphp
                <button onclick="switchCurrency('USD')" 
                        class="flex items-center space-x-2 px-3 py-2 border rounded-lg {{ $currentCurrency == 'USD' ? 'border-black bg-black text-white' : 'border-gray-300 text-gray-700' }}">
                    <span>🇺🇸</span>
                    <span class="text-sm">USD</span>
                </button>
                <button onclick="switchCurrency('MMK')" 
                        class="flex items-center space-x-2 px-3 py-2 border rounded-lg {{ $currentCurrency == 'MMK' ? 'border-black bg-black text-white' : 'border-gray-300 text-gray-700' }}">
                    <span>🇲🇲</span>
                    <span class="text-sm">MMK</span>
                </button>
                <button onclick="switchCurrency('EUR')" 
                        class="flex items-center space-x-2 px-3 py-2 border rounded-lg {{ $currentCurrency == 'EUR' ? 'border-black bg-black text-white' : 'border-gray-300 text-gray-700' }}">
                    <span>🇪🇺</span>
                    <span class="text-sm">EUR</span>
                </button>
                <button onclick="switchCurrency('GBP')" 
                        class="flex items-center space-x-2 px-3 py-2 border rounded-lg {{ $currentCurrency == 'GBP' ? 'border-black bg-black text-white' : 'border-gray-300 text-gray-700' }}">
                    <span>🇬🇧</span>
                    <span class="text-sm">GBP</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Language switching functionality
function switchLanguage(language) {
    fetch('/language/switch', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ language: language })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(`Language switched to ${language === 'en' ? 'English' : 'Myanmar'}`, 'success');
            // Update language display without page reload
            const languageDisplay = document.querySelector('[x-data] span');
            if (languageDisplay) {
                languageDisplay.textContent = language === 'en' ? 'EN' : 'MY';
            }
            // Reload page to apply language changes to all content
            window.location.reload();
        } else {
            showToast('Failed to switch language', 'error');
        }
    })
    .catch(error => {
        console.error('Error switching language:', error);
        showToast('Error switching language', 'error');
    });
}

// Currency conversion rates (for display purposes)
const exchangeRates = {
    USD: 1,
    MMK: 4400,
    EUR: 0.92,
    GBP: 0.79
};

// Format currency based on current selection
function formatCurrency(amount, currency = null) {
    const currentCurrency = currency || '{{ session("currency", "USD") }}';
    const rate = exchangeRates[currentCurrency];
    const convertedAmount = amount * rate;
    
    const symbols = {
        USD: '$',
        MMK: 'K',
        EUR: '€',
        GBP: '£'
    };
    
    const symbol = symbols[currentCurrency];
    
    if (currentCurrency === 'MMK') {
        return symbol + convertedAmount.toLocaleString('en-US', { maximumFractionDigits: 0 });
    } else {
        return symbol + convertedAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
}

// Auto-update currency displays when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Update all currency displays
    const currencyElements = document.querySelectorAll('[data-currency]');
    currencyElements.forEach(element => {
        const amount = parseFloat(element.getAttribute('data-currency'));
        element.textContent = formatCurrency(amount);
    });
});
</script>
