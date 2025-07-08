// Enhanced Super App Carousel with auto-play
document.addEventListener('DOMContentLoaded', function() {
    initSuperAppCarousel();
    addSuperAppAnimations();
});

function initSuperAppCarousel() {
    const container = document.querySelector('.superapp-cards-container');
    const cardsWrapper = document.querySelector('.superapp-cards');
    const cards = document.querySelectorAll('.superapp-card');
    const prevButton = document.querySelector('.features-swiper-button-prev');
    const nextButton = document.querySelector('.features-swiper-button-next');
    
    if (!container || !cardsWrapper || cards.length === 0) return;

    // Clone cards for infinite loop
    const firstCard = cards[0].cloneNode(true);
    const lastCard = cards[cards.length - 1].cloneNode(true);
    cardsWrapper.appendChild(firstCard);
    cardsWrapper.insertBefore(lastCard, cardsWrapper.firstChild);
    
    const allCards = document.querySelectorAll('.superapp-card');
    const cardCount = allCards.length;
    
    // Calculate card width including gap
    const gap = parseInt(window.getComputedStyle(cardsWrapper).gap) || 0;
    const cardWidth = allCards[0].offsetWidth + gap;
    
    // Carousel state
    let currentIndex = 1;
    let startX = 0;
    let currentX = 0;
    let isDragging = false;
    let isAnimating = false;
    let autoPlayInterval;
    let isHovered = false;
    const autoPlayDelay = 2000; // 2 seconds
    
    // Store the carousel state
    window.superAppCarousel = {
        container,
        cardsWrapper,
        cards: allCards,
        cardCount,
        currentIndex,
        cardWidth,
        gap,
        cardsPerView: getCardsPerView(),
        isAnimating,
        isDragging
    };
    
    // Set initial position
    updateCarouselPosition(1, false);
    
    // Setup auto-play
    setupAutoPlay();
    
    // Navigation buttons
    if (prevButton && nextButton) {
        prevButton.addEventListener('click', () => {
            navigateCarousel('prev');
            resetAutoPlay();
        });
        nextButton.addEventListener('click', () => {
            navigateCarousel('next');
            resetAutoPlay();
        });
    }
    
    // Touch events
    container.addEventListener('touchstart', handleTouchStart, { passive: false });
    container.addEventListener('touchmove', handleTouchMove, { passive: false });
    container.addEventListener('touchend', handleTouchEnd);
    
    // Mouse events
    container.addEventListener('mousedown', handleMouseDown);
    container.addEventListener('mousemove', handleMouseMove);
    container.addEventListener('mouseup', handleMouseUp);
    container.addEventListener('mouseleave', handleMouseUp);
    
    // Pause on hover
    container.addEventListener('mouseenter', () => {
        isHovered = true;
        pauseAutoPlay();
    });
    container.addEventListener('mouseleave', () => {
        isHovered = false;
        resumeAutoPlay();
    });
    
    // Update on window resize
    window.addEventListener('resize', function() {
        const carousel = window.superAppCarousel;
        if (!carousel) return;
        
        carousel.cardWidth = carousel.cards[0].offsetWidth + carousel.gap;
        carousel.cardsPerView = getCardsPerView();
        updateCarouselPosition(carousel.currentIndex, false);
        resetAutoPlay();
    });
    
    function setupAutoPlay() {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(() => {
            if (!isHovered && !isDragging && !isAnimating) {
                navigateCarousel('next');
            }
        }, autoPlayDelay);
    }
    
    function pauseAutoPlay() {
        clearInterval(autoPlayInterval);
    }
    
    function resumeAutoPlay() {
        if (!isHovered) {
            setupAutoPlay();
        }
    }
    
    function resetAutoPlay() {
        pauseAutoPlay();
        resumeAutoPlay();
    }
    
    function handleTouchStart(e) {
        e.preventDefault();
        startX = e.touches[0].clientX;
        currentX = startX;
        isDragging = true;
        cardsWrapper.classList.add('grabbing');
        pauseAutoPlay();
    }
    
    function handleTouchMove(e) {
        if (!isDragging) return;
        e.preventDefault();
        
        const x = e.touches[0].clientX;
        const diff = x - currentX;
        currentX = x;
        
        const carousel = window.superAppCarousel;
        const currentPos = -carousel.currentIndex * carousel.cardWidth;
        const newPos = currentPos + diff;
        
        cardsWrapper.style.transform = `translateX(${newPos}px)`;
    }
    
    function handleTouchEnd() {
        if (!isDragging) return;
        isDragging = false;
        cardsWrapper.classList.remove('grabbing');
        
        const carousel = window.superAppCarousel;
        const distance = currentX - startX;
        const velocity = Math.abs(distance) / 10;
        
        if (Math.abs(distance) > 50 || velocity > 5) {
            const direction = distance > 0 ? 'prev' : 'next';
            navigateCarousel(direction, velocity);
        } else {
            updateCarouselPosition(carousel.currentIndex);
        }
        
        resumeAutoPlay();
    }
    
    function handleMouseDown(e) {
        e.preventDefault();
        startX = e.clientX;
        currentX = startX;
        isDragging = true;
        cardsWrapper.classList.add('grabbing');
        pauseAutoPlay();
    }
    
    function handleMouseMove(e) {
        if (!isDragging) return;
        e.preventDefault();
        
        const x = e.clientX;
        const diff = x - currentX;
        currentX = x;
        
        const carousel = window.superAppCarousel;
        const currentPos = -carousel.currentIndex * carousel.cardWidth;
        const newPos = currentPos + diff;
        
        cardsWrapper.style.transform = `translateX(${newPos}px)`;
    }
    
    function handleMouseUp() {
        if (!isDragging) return;
        isDragging = false;
        cardsWrapper.classList.remove('grabbing');
        
        const carousel = window.superAppCarousel;
        const distance = currentX - startX;
        
        if (Math.abs(distance) > 50) {
            const direction = distance > 0 ? 'prev' : 'next';
            navigateCarousel(direction);
        } else {
            updateCarouselPosition(carousel.currentIndex);
        }
        
        resumeAutoPlay();
    }
    
    function navigateCarousel(direction, velocity = 1) {
        const carousel = window.superAppCarousel;
        if (!carousel || carousel.isAnimating) return;
        
        carousel.isAnimating = true;
        
        // Calculate duration based on velocity (smoother auto-play)
        const duration = direction === 'auto' 
            ? 800 // Slightly slower for auto-play for better visibility
            : Math.min(600, 600 / Math.min(Math.max(velocity, 0.5), 2));
        
        cardsWrapper.style.transitionDuration = `${duration}ms`;
        
        let newIndex;
        if (direction === 'next' || direction === 'auto') {
            newIndex = carousel.currentIndex + 1;
            if (newIndex >= carousel.cardCount - 1) {
                setTimeout(() => {
                    updateCarouselPosition(1, false);
                    carousel.currentIndex = 1;
                }, duration);
                newIndex = carousel.cardCount - 1;
            }
        } else {
            newIndex = carousel.currentIndex - 1;
            if (newIndex <= 0) {
                setTimeout(() => {
                    updateCarouselPosition(carousel.cardCount - 2, false);
                    carousel.currentIndex = carousel.cardCount - 2;
                }, duration);
                newIndex = 0;
            }
        }
        
        carousel.currentIndex = newIndex;
        updateCarouselPosition(newIndex);
        
        cardsWrapper.addEventListener('transitionend', function handler() {
            cardsWrapper.removeEventListener('transitionend', handler);
            carousel.isAnimating = false;
            cardsWrapper.style.transitionDuration = '0.6s';
        }, { once: true });
    }
    
    function updateCarouselPosition(index, animate = true) {
        const carousel = window.superAppCarousel;
        if (!carousel) return;
        
        const offset = -index * carousel.cardWidth;
        carousel.cardsWrapper.style.transition = animate 
            ? `transform ${carousel.cardsWrapper.style.transitionDuration || '0.6s'} cubic-bezier(0.25, 0.46, 0.45, 0.94)` 
            : 'none';
        carousel.cardsWrapper.style.transform = `translateX(${offset}px)`;
    }
}

function getCardsPerView() {
    if (window.innerWidth >= 1024) return 3;
    if (window.innerWidth >= 768) return 2;
    return 1;
}

function addSuperAppAnimations() {
    // Your existing animation code
}