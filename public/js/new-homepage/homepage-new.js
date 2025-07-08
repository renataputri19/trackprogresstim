/**
 * RENTAK Homepage - Simple Manual Swiper Implementation
 * This script provides a simple, lightweight swiper functionality
 * without relying on external libraries.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Force light mode as per user preference
    document.documentElement.classList.remove("dark");
    document.documentElement.classList.add("light");
    document.body.classList.add("light");
    document.body.classList.remove("dark");
    localStorage.setItem("theme", "light");

    // Initialize the simple swiper
    initSimpleSwiper();

    // Smooth scroll for anchor links
    initSmoothScroll();
});

/**
 * Initialize the simple swiper functionality
 */
function initSimpleSwiper() {
    const swipers = document.querySelectorAll('.rentak-swiper-container');
    
    swipers.forEach(swiper => {
        const wrapper = swiper.querySelector('.rentak-swiper-wrapper');
        const slides = swiper.querySelectorAll('.rentak-swiper-slide');
        const pagination = swiper.querySelector('.rentak-swiper-pagination');
        const nextButton = swiper.querySelector('.rentak-swiper-button-next');
        const prevButton = swiper.querySelector('.rentak-swiper-button-prev');
        
        if (!wrapper || slides.length === 0) return;
        
        // Set initial state
        let currentIndex = 0;
        let slideWidth = 100; // percentage
        let slidesPerView = 1;
        
        // Determine slides per view based on screen width
        if (window.innerWidth >= 1024) {
            slidesPerView = 3;
        } else if (window.innerWidth >= 768) {
            slidesPerView = 2;
        }
        
        // Calculate slide width as percentage
        slideWidth = 100 / slidesPerView;
        
        // Set initial styles
        slides.forEach(slide => {
            slide.style.width = `${slideWidth}%`;
        });
        
        // Create pagination bullets
        if (pagination) {
            for (let i = 0; i < Math.ceil(slides.length / slidesPerView); i++) {
                const bullet = document.createElement('div');
                bullet.classList.add('rentak-swiper-bullet');
                if (i === 0) bullet.classList.add('active');
                
                bullet.addEventListener('click', () => {
                    goToSlide(i);
                });
                
                pagination.appendChild(bullet);
            }
        }
        
        // Add event listeners for buttons
        if (nextButton) {
            nextButton.addEventListener('click', () => {
                goToSlide(currentIndex + 1);
            });
        }
        
        if (prevButton) {
            prevButton.addEventListener('click', () => {
                goToSlide(currentIndex - 1);
            });
        }
        
        // Add touch/swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        
        swiper.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        swiper.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipeGesture();
        }, { passive: true });
        
        function handleSwipeGesture() {
            const minSwipeDistance = 50;
            const swipeDistance = touchEndX - touchStartX;
            
            if (swipeDistance > minSwipeDistance) {
                // Swiped right
                goToSlide(currentIndex - 1);
            } else if (swipeDistance < -minSwipeDistance) {
                // Swiped left
                goToSlide(currentIndex + 1);
            }
        }
        
        // Function to go to a specific slide
        function goToSlide(index) {
            const maxIndex = Math.ceil(slides.length / slidesPerView) - 1;
            
            // Handle bounds
            if (index < 0) {
                index = 0;
            } else if (index > maxIndex) {
                index = maxIndex;
            }
            
            currentIndex = index;
            
            // Update transform
            const translateX = -index * (slideWidth * slidesPerView);
            wrapper.style.transform = `translateX(${translateX}%)`;
            
            // Update pagination
            if (pagination) {
                const bullets = pagination.querySelectorAll('.rentak-swiper-bullet');
                bullets.forEach((bullet, i) => {
                    if (i === index) {
                        bullet.classList.add('active');
                    } else {
                        bullet.classList.remove('active');
                    }
                });
            }
            
            // Update button states (optional)
            if (prevButton) {
                prevButton.style.opacity = index === 0 ? '0.5' : '1';
            }
            
            if (nextButton) {
                nextButton.style.opacity = index === maxIndex ? '0.5' : '1';
            }
        }
        
        // Handle window resize
        window.addEventListener('resize', () => {
            let newSlidesPerView = 1;
            
            if (window.innerWidth >= 1024) {
                newSlidesPerView = 3;
            } else if (window.innerWidth >= 768) {
                newSlidesPerView = 2;
            }
            
            // Only update if slides per view has changed
            if (newSlidesPerView !== slidesPerView) {
                slidesPerView = newSlidesPerView;
                slideWidth = 100 / slidesPerView;
                
                // Update slide widths
                slides.forEach(slide => {
                    slide.style.width = `${slideWidth}%`;
                });
                
                // Reset pagination
                if (pagination) {
                    pagination.innerHTML = '';
                    for (let i = 0; i < Math.ceil(slides.length / slidesPerView); i++) {
                        const bullet = document.createElement('div');
                        bullet.classList.add('rentak-swiper-bullet');
                        if (i === 0) bullet.classList.add('active');
                        
                        bullet.addEventListener('click', () => {
                            goToSlide(i);
                        });
                        
                        pagination.appendChild(bullet);
                    }
                }
                
                // Go to first slide
                goToSlide(0);
            }
        });
    });
}

/**
 * Initialize smooth scrolling for anchor links
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
}
