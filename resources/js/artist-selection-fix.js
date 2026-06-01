// Fix for Change Artist button functionality
document.addEventListener('DOMContentLoaded', function() {
    // Find the Change Artist button and add the onclick handler
    const changeArtistButton = document.querySelector('#selected_artist_summary button');
    if (changeArtistButton) {
        changeArtistButton.addEventListener('click', function() {
            // Call the existing changeArtistSelection function if it exists
            if (typeof changeArtistSelection === 'function') {
                changeArtistSelection();
            } else {
                // Fallback implementation
                const summaryDiv = document.getElementById('selected_artist_summary');
                const cardsGrid = document.getElementById('artist_cards');
                const artistSelect = document.getElementById('artist_id');
                const artistCards = document.querySelectorAll('.artist-card');
                
                if (summaryDiv) {
                    summaryDiv.classList.add('hidden');
                }
                
                if (cardsGrid) {
                    cardsGrid.style.opacity = '1';
                    cardsGrid.style.pointerEvents = 'auto';
                }
                
                artistCards.forEach(card => {
                    card.classList.remove('ring-4', 'ring-green-400', 'bg-green-50', 'border-green-400');
                    card.classList.add('border-transparent');
                    const indicator = card.querySelector('.artist-selection-indicator');
                    if (indicator) indicator.classList.add('hidden');
                });
                
                if (artistSelect) {
                    artistSelect.value = '';
                }
                
                // Update progress back to step 1
                document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                    const stepNum = index + 1;
                    if (stepNum === 1) {
                        indicator.classList.remove('bg-gray-300', 'text-gray-600');
                        indicator.classList.add('bg-red-600', 'text-white');
                    } else {
                        indicator.classList.remove('bg-red-600', 'text-white');
                        indicator.classList.add('bg-gray-300', 'text-gray-600');
                    }
                });
                
                document.querySelectorAll('.step-line').forEach(line => {
                    line.classList.remove('bg-red-600');
                    line.classList.add('bg-gray-300');
                });
            }
        });
    }
});
