document.addEventListener('DOMContentLoaded', function() {
    // Progress tracking
    let currentStep = 1;
    const totalSteps = 4;
    
    function updateProgress(step) {
        currentStep = step;
        document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
            const stepNum = index + 1;
            if (stepNum <= step) {
                indicator.classList.remove('bg-gray-300', 'text-gray-600');
                indicator.classList.add('bg-red-600', 'text-white');
            } else {
                indicator.classList.remove('bg-red-600', 'text-white');
                indicator.classList.add('bg-gray-300', 'text-gray-600');
            }
        });
        
        document.querySelectorAll('.step-line').forEach((line, index) => {
            if (index < step - 1) {
                line.classList.remove('bg-gray-300');
                line.classList.add('bg-red-600');
            } else {
                line.classList.remove('bg-red-600');
                line.classList.add('bg-gray-300');
            }
        });
    }
    
    // Artist selection functionality
    let selectedArtist = null;
    const artistCards = document.querySelectorAll('.artist-card');
    const artistSelect = document.getElementById('artist_id');
    const artistSearch = document.getElementById('artist_search');
    const sortArtists = document.getElementById('sort_artists');
    const activeFilters = document.getElementById('active_filters');
    const noResults = document.getElementById('no_results');
    let currentFilters = {
        search: '',
        sort: 'name',
        type: 'all'
    };
    
    // Artist card selection
    artistCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                return;
            }
            
            artistCards.forEach(c => {
                c.classList.remove('ring-4', 'ring-red-400', 'bg-red-50', 'border-red-400');
                c.classList.add('border-transparent');
                const indicator = c.querySelector('.artist-selection-indicator');
                if (indicator) indicator.classList.add('hidden');
            });
            
            this.classList.remove('border-transparent');
            this.classList.add('ring-4', 'ring-green-400', 'bg-green-50', 'border-green-400');
            const indicator = this.querySelector('.artist-selection-indicator');
            if (indicator) indicator.classList.remove('hidden');
            
            const artistId = this.dataset.artistId;
            artistSelect.value = artistId;
            selectedArtist = this.dataset.artistName;
            
            updateSelectedArtistSummary(this);
            
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            
            updateProgress(2);
        });
    });
    
    function updateSelectedArtistSummary(selectedCard) {
        const summaryDiv = document.getElementById('selected_artist_summary');
        const avatarImg = document.getElementById('selected_artist_avatar');
        const nameSpan = document.getElementById('selected_artist_name');
        const specialtySpan = document.getElementById('selected_artist_specialty');
        
        if (summaryDiv && avatarImg && nameSpan && specialtySpan) {
            const artistName = selectedCard.dataset.artistName;
            const artistSpecialty = selectedCard.dataset.artistSpecialty;
            const artistAvatar = selectedCard.querySelector('img').src;
            
            avatarImg.src = artistAvatar;
            avatarImg.alt = artistName;
            nameSpan.textContent = artistName;
            specialtySpan.textContent = artistSpecialty;
            
            summaryDiv.classList.remove('hidden');
            summaryDiv.style.opacity = '0';
            summaryDiv.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                summaryDiv.style.transition = 'all 0.3s ease';
                summaryDiv.style.opacity = '1';
                summaryDiv.style.transform = 'translateY(0)';
            }, 100);
            
            setTimeout(() => {
                const cardsGrid = document.getElementById('artist_cards');
                if (cardsGrid) {
                    cardsGrid.style.opacity = '0.6';
                    cardsGrid.style.pointerEvents = 'none';
                }
            }, 500);
        }
    }
    
    // Change artist selection function
    window.changeArtistSelection = function() {
        const summaryDiv = document.getElementById('selected_artist_summary');
        const cardsGrid = document.getElementById('artist_cards');
        
        if (summaryDiv) {
            summaryDiv.classList.add('hidden');
        }
        
        if (cardsGrid) {
            cardsGrid.style.opacity = '1';
            cardsGrid.style.pointerEvents = 'auto';
        }
        
        artistCards.forEach(c => {
            c.classList.remove('ring-4', 'ring-green-400', 'bg-green-50', 'border-green-400');
            c.classList.add('border-transparent');
            const indicator = c.querySelector('.artist-selection-indicator');
            if (indicator) indicator.classList.add('hidden');
        });
        
        artistSelect.value = '';
        selectedArtist = null;
        updateProgress(1);
    };
    
    // Search functionality
    if (artistSearch) {
        artistSearch.addEventListener('input', function() {
            currentFilters.search = this.value.toLowerCase();
            applyFilters();
            updateActiveFilters();
        });
    }
    
    // Sort functionality
    if (sortArtists) {
        sortArtists.addEventListener('change', function() {
            currentFilters.sort = this.value;
            applyFilters();
        });
    }
    
    function applyFilters() {
        let visibleCount = 0;
        const sortedCards = Array.from(artistCards);
        
        sortedCards.sort((a, b) => {
            switch (currentFilters.sort) {
                case 'name':
                    return a.dataset.artistName.localeCompare(b.dataset.artistName);
                case 'artworks':
                    return parseInt(b.dataset.artistArtworks) - parseInt(a.dataset.artistArtworks);
                case 'verified':
                    const aVerified = a.dataset.artistVerified === 'true';
                    const bVerified = b.dataset.artistVerified === 'true';
                    if (aVerified && !bVerified) return -1;
                    if (!aVerified && bVerified) return 1;
                    return 0;
                default:
                    return 0;
            }
        });
        
        const container = document.getElementById('artist_cards');
        sortedCards.forEach(card => {
            const name = card.dataset.artistName.toLowerCase();
            const specialty = card.dataset.artistSpecialty.toLowerCase();
            const artworks = parseInt(card.dataset.artistArtworks);
            
            let matches = true;
            
            if (currentFilters.search && !name.includes(currentFilters.search) && !specialty.includes(currentFilters.search)) {
                matches = false;
            }
            
            if (currentFilters.type === 'popular' && artworks < 5) {
                matches = false;
            }
            
            if (matches) {
                card.style.display = 'block';
                container.appendChild(card);
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        if (noResults) {
            noResults.classList.toggle('hidden', visibleCount > 0);
        }
    }
    
    function updateActiveFilters() {
        if (!activeFilters) return;
        
        activeFilters.innerHTML = '';
        
        if (currentFilters.search) {
            addFilterTag('Search: ' + currentFilters.search, 'search');
        }
        
        if (currentFilters.type === 'popular') {
            addFilterTag('Popular Artists', 'popular');
        }
    }
    
    function addFilterTag(text, type) {
        const tag = document.createElement('div');
        tag.className = 'inline-flex items-center gap-2 px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm';
        tag.innerHTML = `
            <span>${text}</span>
            <button type="button" class="hover:text-red-900" data-filter-type="${type}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;
        activeFilters.appendChild(tag);
    }
    
    // Remove filter function
    window.removeFilter = function(type) {
        if (type === 'search') {
            currentFilters.search = '';
            if (artistSearch) artistSearch.value = '';
        } else if (type === 'popular') {
            currentFilters.type = 'all';
        }
        applyFilters();
        updateActiveFilters();
    };
    
    // Filter artists function
    window.filterArtists = function(type) {
        currentFilters.type = type;
        applyFilters();
        updateActiveFilters();
        
        document.querySelectorAll('[data-filter-type]').forEach(btn => {
            if (btn.getAttribute('data-filter-type') === type) {
                btn.classList.remove('bg-gray-200', 'text-gray-700');
                btn.classList.add('bg-red-600', 'text-white');
            } else {
                btn.classList.remove('bg-red-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            }
        });
    };
    
    // Attach filter button event listeners
    document.querySelectorAll('[data-filter-btn]').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.getAttribute('data-filter-btn');
            filterArtists(type);
        });
    });
    
    // View artist details function
    window.viewArtistDetails = function(artistId) {
        if (!artistId) return;
        window.location.href = `/artists/${artistId}`;
    };
    
    // View portfolio function
    window.viewPortfolio = function(artistId) {
        if (!artistId) return;
        window.location.href = `/artists/${artistId}#portfolio`;
    };

    // Attach portfolio and details button event listeners
    document.querySelectorAll('[data-portfolio-btn]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            viewPortfolio(this.dataset.portfolioBtn);
        });
    });

    document.querySelectorAll('[data-details-btn]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            viewArtistDetails(this.dataset.detailsBtn);
        });
    });
    
    // Shape selection
    let selectedShape = null;
    const shapeOptions = document.querySelectorAll('.shape-option');
    const artworkShapeInput = document.getElementById('artwork_shape');
    
    shapeOptions.forEach(option => {
        option.addEventListener('click', function() {
            shapeOptions.forEach(opt => {
                opt.classList.remove('ring-4', 'ring-red-400', 'bg-red-50');
            });
            
            this.classList.add('ring-4', 'ring-red-400', 'bg-red-50');
            
            selectedShape = this.dataset.shape;
            artworkShapeInput.value = selectedShape;
            
            const shapeDisplay = document.getElementById('shape_display');
            if (shapeDisplay) {
                shapeDisplay.textContent = selectedShape.charAt(0).toUpperCase() + selectedShape.slice(1);
            }
            
            document.querySelectorAll('[id$="_dims"]').forEach(dim => {
                dim.classList.add('hidden');
            });
            
            const targetDims = document.getElementById(`${selectedShape}_dims`);
            if (targetDims) {
                targetDims.classList.remove('hidden');
                targetDims.querySelectorAll('input').forEach(input => {
                    input.required = true;
                });
                
                setTimeout(() => {
                    updateShapePreview();
                }, 0);
            }
            
            const shapePreview = document.getElementById('shape_preview');
            if (shapePreview) {
                shapePreview.innerHTML = getInitialShapePreview(selectedShape);
            }
            
            updateProgress(2);
        });
    });
    
    function getInitialShapePreview(shape) {
        switch(shape) {
            case 'rectangle':
                return '<div class="bg-red-200 rounded" style="width: 60px; height: 40px;"></div>';
            case 'square':
                return '<div class="bg-red-200 rounded" style="width: 50px; height: 50px;"></div>';
            case 'circle':
                return '<div class="bg-red-200 rounded-full" style="width: 50px; height: 50px;"></div>';
            case 'oval':
                return '<div class="bg-red-200 rounded-full" style="width: 60px; height: 35px;"></div>';
            case 'triangle':
                return '<div style="width: 0; height: 0; border-left: 25px solid transparent; border-right: 25px solid transparent; border-bottom: 40px solid rgb(254 202 202);"></div>';
            case 'custom':
                return '<div class="text-2xl text-red-600">?</div>';
            default:
                return '<div class="text-gray-400">?</div>';
        }
    }
    
    function updateShapePreview() {
        const shapeDisplay = document.getElementById('shape_display');
        const sizeDisplay = document.getElementById('size_display');
        const areaDisplay = document.getElementById('area_display');
        const sizeMultiplier = document.getElementById('size_multiplier');
        const priceMultiplier = document.getElementById('price_multiplier');
        const shapePreview = document.getElementById('shape_preview');
        
        if (!selectedShape) {
            if (shapeDisplay) shapeDisplay.textContent = '--';
            if (sizeDisplay) sizeDisplay.textContent = '--';
            if (areaDisplay) areaDisplay.textContent = '--';
            if (sizeMultiplier) sizeMultiplier.textContent = '1.0x';
            if (priceMultiplier) priceMultiplier.textContent = '1.0x';
            return;
        }
        
        let dimensions = {};
        let area = 0;
        let multiplier = 1.0;
        let previewHTML = '';
        
        switch(selectedShape) {
            case 'rectangle':
                dimensions.width = parseFloat(document.getElementById('rect_width')?.value) || 0;
                dimensions.height = parseFloat(document.getElementById('rect_height')?.value) || 0;
                area = dimensions.width * dimensions.height;
                multiplier = getMultiplierFromArea(area);
                previewHTML = createRectanglePreview(dimensions.width, dimensions.height);
                break;
            case 'square':
                dimensions.size = parseFloat(document.getElementById('square_size')?.value) || 0;
                dimensions.width = dimensions.size;
                dimensions.height = dimensions.size;
                area = dimensions.size * dimensions.size;
                multiplier = getMultiplierFromArea(area);
                previewHTML = createSquarePreview(dimensions.size);
                break;
            case 'circle':
                dimensions.diameter = parseFloat(document.getElementById('circle_diameter')?.value) || 0;
                dimensions.radius = dimensions.diameter / 2;
                area = Math.PI * dimensions.radius * dimensions.radius;
                multiplier = getMultiplierFromArea(area);
                previewHTML = createCirclePreview(dimensions.diameter);
                break;
            case 'oval':
                dimensions.width = parseFloat(document.getElementById('oval_width')?.value) || 0;
                dimensions.height = parseFloat(document.getElementById('oval_height')?.value) || 0;
                area = Math.PI * (dimensions.width/2) * (dimensions.height/2);
                multiplier = getMultiplierFromArea(area);
                previewHTML = createOvalPreview(dimensions.width, dimensions.height);
                break;
            case 'triangle':
                dimensions.base = parseFloat(document.getElementById('triangle_base')?.value) || 0;
                dimensions.height = parseFloat(document.getElementById('triangle_height')?.value) || 0;
                area = 0.5 * dimensions.base * dimensions.height;
                multiplier = getMultiplierFromArea(area);
                previewHTML = createTrianglePreview(dimensions.base, dimensions.height);
                break;
            case 'custom':
                dimensions.maxWidth = parseFloat(document.getElementById('custom_max_width')?.value) || 0;
                dimensions.maxHeight = parseFloat(document.getElementById('custom_max_height')?.value) || 0;
                area = dimensions.maxWidth * dimensions.maxHeight * 0.7;
                multiplier = getMultiplierFromArea(area);
                previewHTML = createCustomPreview();
                break;
        }
        
        if (area > 0) {
            if (shapeDisplay) {
                shapeDisplay.textContent = selectedShape.charAt(0).toUpperCase() + selectedShape.slice(1);
            }
            
            let dimensionText = '';
            if (selectedShape === 'square') {
                dimensionText = `${dimensions.size}" × ${dimensions.size}"`;
            } else if (selectedShape === 'circle') {
                dimensionText = `Ø ${dimensions.diameter}"`;
            } else if (selectedShape === 'custom') {
                dimensionText = `Max: ${dimensions.maxWidth}" × ${dimensions.maxHeight}"`;
            } else if (dimensions.width && dimensions.height) {
                dimensionText = `${dimensions.width}" × ${dimensions.height}"`;
            }
            
            if (sizeDisplay) sizeDisplay.textContent = dimensionText;
            if (areaDisplay) areaDisplay.textContent = Math.round(area).toLocaleString();
            if (sizeMultiplier) sizeMultiplier.textContent = `${multiplier}x`;
            if (priceMultiplier) priceMultiplier.textContent = `${multiplier}x`;
            
            if (shapePreview) {
                shapePreview.innerHTML = previewHTML;
            }
            
            updatePriceCalculation();
        }
    }
    
    function getMultiplierFromArea(area) {
        if (area < 100) return 1.0;
        if (area < 200) return 1.2;
        if (area < 400) return 1.5;
        if (area < 800) return 2.0;
        if (area < 1500) return 2.5;
        return 3.0;
    }
    
    function createRectanglePreview(width, height) {
        const scale = Math.min(80 / width, 80 / height);
        const previewWidth = width * scale;
        const previewHeight = height * scale;
        return `<div class="bg-red-200 rounded" style="width: ${previewWidth}px; height: ${previewHeight}px;"></div>`;
    }
    
    function createSquarePreview(size) {
        const scale = Math.min(80 / size);
        const previewSize = size * scale;
        return `<div class="bg-red-200 rounded" style="width: ${previewSize}px; height: ${previewSize}px;"></div>`;
    }
    
    function createCirclePreview(diameter) {
        const scale = Math.min(80 / diameter);
        const previewDiameter = diameter * scale;
        return `<div class="bg-red-200 rounded-full" style="width: ${previewDiameter}px; height: ${previewDiameter}px;"></div>`;
    }
    
    function createOvalPreview(width, height) {
        const scale = Math.min(80 / width, 80 / height);
        const previewWidth = width * scale;
        const previewHeight = height * scale;
        return `<div class="bg-red-200 rounded-full" style="width: ${previewWidth}px; height: ${previewHeight}px;"></div>`;
    }
    
    function createTrianglePreview(base, height) {
        const scale = Math.min(80 / base, 80 / height);
        const previewBase = base * scale;
        const previewHeight = height * scale;
        return `<div style="width: 0; height: 0; border-left: ${previewBase/2}px solid transparent; border-right: ${previewBase/2}px solid transparent; border-bottom: ${previewHeight}px solid rgb(254 202 202);"></div>`;
    }
    
    function createCustomPreview() {
        return `<div class="text-2xl text-red-600">?</div>`;
    }
    
    function updatePriceCalculation() {
        // Get the proposed price from the input field
        const proposedPriceInput = document.getElementById('proposed_price');
        const basePrice = parseFloat(proposedPriceInput?.value) || 0;
        
        // Calculate total: Proposed Price + Shipping Fee ($150) + Additional Charges ($100)
        const shippingFee = 150;
        const additionalCharges = 100;
        const total = basePrice + shippingFee + additionalCharges;
        
        // FORCE UPDATE the estimated total display
        const estimatedTotalEl = document.getElementById('estimated_total');
        if (estimatedTotalEl) {
            const formattedTotal = `$${total.toFixed(2)}`;
            estimatedTotalEl.textContent = formattedTotal;
            estimatedTotalEl.innerText = formattedTotal;
            estimatedTotalEl.innerHTML = formattedTotal;
            console.log('FORCE Updated total to:', formattedTotal, 'from basePrice:', basePrice);
        } else {
            console.error('Estimated total element not found!');
        }
        
        console.log('Price calculation:', { basePrice, shippingFee, additionalCharges, total });
    }
    
    // Add input handlers for dimension fields
    const dimensionInputs = ['rect_width', 'rect_height', 'square_size', 'circle_diameter', 'oval_width', 'oval_height', 'triangle_base', 'triangle_height', 'custom_max_width', 'custom_max_height', 'custom_description'];
    dimensionInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', updateShapePreview);
        }
    });
    
    // Proposed price input handler - AGGRESSIVE approach for reliability
    const proposedPriceInput = document.getElementById('proposed_price');
    if (proposedPriceInput) {
        // IMMEDIATE calculation on any input
        proposedPriceInput.addEventListener('input', function() {
            console.log('INPUT event triggered, value:', this.value);
            updatePriceCalculation();
            
            const price = parseFloat(this.value);
            const priceError = document.getElementById('price_error');
            
            // Real-time validation
            if (price && price < 200) {
                if (!priceError) {
                    const errorDiv = document.createElement('p');
                    errorDiv.id = 'price_error';
                    errorDiv.className = 'mt-2 text-sm text-red-600';
                    errorDiv.textContent = 'Price must be at least $200';
                    this.parentNode.parentNode.appendChild(errorDiv);
                }
                this.classList.add('border-red-500');
                this.classList.remove('border-green-500');
            } else if (price >= 200) {
                if (priceError) {
                    priceError.remove();
                }
                this.classList.remove('border-red-500');
                this.classList.add('border-green-500');
            } else {
                if (priceError) {
                    priceError.remove();
                }
                this.classList.remove('border-red-500', 'border-green-500');
            }
            
            updateProgress(3);
        });
        
        // Also trigger on keyup for immediate response
        proposedPriceInput.addEventListener('keyup', function() {
            console.log('KEYUP event triggered, value:', this.value);
            updatePriceCalculation();
        });
        
        // Also trigger on keydown
        proposedPriceInput.addEventListener('keydown', function() {
            setTimeout(() => updatePriceCalculation(), 10);
        });
        
        // Also trigger calculation on page load
        proposedPriceInput.addEventListener('change', updatePriceCalculation);
        
        // Also trigger on blur
        proposedPriceInput.addEventListener('blur', updatePriceCalculation);
        
        // Also trigger on focus
        proposedPriceInput.addEventListener('focus', updatePriceCalculation);
        
        console.log('AGGRESSIVE Price input event listeners attached');
    } else {
        console.error('Proposed price input not found!');
    }
    
    // Set up a continuous checker as backup
    setInterval(() => {
        const priceInput = document.getElementById('proposed_price');
        if (priceInput && priceInput.value) {
            updatePriceCalculation();
        }
    }, 500);
    
    // Medium and style change handlers
    const mediumSelect = document.getElementById('medium');
    const styleSelect = document.getElementById('style');
    
    if (mediumSelect) {
        mediumSelect.addEventListener('change', function() {
            if (this.value) updateProgress(2);
        });
    }
    
    if (styleSelect) {
        styleSelect.addEventListener('change', function() {
            if (this.value) updateProgress(2);
        });
    }
    
    const descriptionInput = document.getElementById('description');
    if (descriptionInput) {
        descriptionInput.addEventListener('input', function() {
            if (this.value.length >= 20) updateProgress(2);
        });
    }
    
    // Image preview functionality
    const referenceImages = document.getElementById('reference_images');
    const imagePreview = document.getElementById('image_preview');
    
    if (referenceImages && imagePreview) {
        referenceImages.addEventListener('change', function(e) {
            imagePreview.innerHTML = '';
            
            Array.from(e.target.files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'relative group';
                        previewDiv.innerHTML = `
                            <img src="${e.target.result}" alt="Reference ${index + 1}" 
                                 class="w-full h-32 object-cover rounded-lg border-2 border-red-300">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-lg flex items-center justify-center">
                                <span class="text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-all">
                                    ${file.name}
                                </span>
                            </div>
                            <button type="button" class="absolute top-2 right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                                ×
                            </button>
                        `;
                        previewDiv.querySelector('button').addEventListener('click', function() {
                            previewDiv.remove();
                        });
                        imagePreview.appendChild(previewDiv);
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            if (e.target.files.length > 0) updateProgress(3);
        });
    }
    
    // Form validation
    const form = document.getElementById('order_form');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';
            
            if (!artistSelect.value) {
                errorMessage = 'Please select an artist.';
                isValid = false;
            }
            
            if (!artworkShapeInput.value) {
                errorMessage = 'Please select a shape for your artwork.';
                isValid = false;
            }
            
            const price = parseFloat(proposedPrice?.value);
            if (!price || price < 200 || price > 10000) {
                errorMessage = 'Price must be between $200 and $10,000.';
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                alert(errorMessage);
                return false;
            }
            
            updateProgress(4);
        });
    }
    
    // View artist portfolio function
    window.viewArtistPortfolio = function(artistId) {
        console.log('View portfolio for artist:', artistId);
        alert(`Portfolio view for artist ${artistId} would open here.`);
    };
    
    // Attach portfolio button event listeners
    document.querySelectorAll('[data-portfolio-btn]').forEach(btn => {
        btn.addEventListener('click', function() {
            const artistId = this.getAttribute('data-portfolio-btn');
            viewArtistPortfolio(artistId);
        });
    });
    
    // Attach details button event listeners
    document.querySelectorAll('[data-details-btn]').forEach(btn => {
        btn.addEventListener('click', function() {
            const artistId = this.getAttribute('data-details-btn');
            viewArtistDetails(artistId);
        });
    });
    
    // Attach change artist button event listener
    const changeArtistBtn = document.querySelector('[data-change-artist-btn]');
    if (changeArtistBtn) {
        changeArtistBtn.addEventListener('click', function() {
            changeArtistSelection();
        });
    }
    
    // Initialize
    updateProgress(1);
    
    // Trigger initial price calculation
    setTimeout(() => {
        updatePriceCalculation();
        console.log('Price calculation initialized');
    }, 100);
    
    // Test function for debugging
    window.testPriceCalculation = function() {
        const testPrice = 500;
        document.getElementById('proposed_price').value = testPrice;
        updatePriceCalculation();
        console.log('Test calculation with $500');
    };
});
