// Initialize Swiper instances immediately to prevent layout shift
// This function runs as soon as the script is loaded
(function() {
    // Pre-initialize Swiper containers to prevent layout shift
    const featuresSwiper = document.querySelector('#features-swiper');
    if (featuresSwiper) {
        featuresSwiper.style.visibility = 'hidden';
    }
})();

document.addEventListener('DOMContentLoaded', function() {
    // Force light mode as per user preference
    document.documentElement.classList.remove("dark");
    document.documentElement.classList.add("light");
    document.body.classList.add("light");
    document.body.classList.remove("dark");
    localStorage.setItem("theme", "light");

    // Check if Swiper elements exist to avoid errors
    if (document.querySelector('.swiper')) {
        const swiper = new Swiper('.swiper', {
            slidesPerView: 1,
            spaceBetween: 32,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    }

    // Smooth scroll for anchor links
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

    // Initialize Dashboard Swiper
    const dashboardSwiper = new Swiper('#dashboard-swiper', {
        slidesPerView: 1,
        spaceBetween: 0,
        centeredSlides: true,
        loop: true,
        speed: 1000,
        effect: 'fade', // Smooth fade effect between slides
        fadeEffect: {
            crossFade: true
        },
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.dashboard-swiper-pagination',
            clickable: true,
            renderBullet: function (index, className) {
                return '<span class="' + className + ' w-2.5 h-2.5 bg-teal-200 dark:bg-teal-800 rounded-full transition-all duration-300 hover:bg-teal-500 dark:hover:bg-teal-600"></span>';
            },
        },
        grabCursor: true,
        on: {
            init: function() {
                console.log('Dashboard Swiper initialized successfully');
            }
        }
    });

    // Initialize Enhanced Features Swiper
    const featuresSwiper = new Swiper('#features-swiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        centeredSlides: true,
        loop: true,
        speed: 800, // Smoother transition speed
        effect: 'coverflow', // More modern effect
        coverflowEffect: {
            rotate: 0,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: false,
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true, // Pause on hover
        },
        pagination: {
            el: '.features-swiper-pagination',
            clickable: true,
            dynamicBullets: true, // Dynamic bullets for better UX
            renderBullet: function (index, className) {
                return '<span class="' + className + ' w-3 h-3 bg-teal-200 dark:bg-teal-800 rounded-full transition-all duration-300 hover:bg-teal-500 dark:hover:bg-teal-600"></span>';
            },
        },
        navigation: {
            nextEl: '.features-swiper-button-next',
            prevEl: '.features-swiper-button-prev',
        },
        grabCursor: true, // Shows grab cursor when hovering over the slider
        keyboard: {
            enabled: true, // Enables keyboard control
        },
        breakpoints: {
            // when window width is >= 640px
            640: {
                slidesPerView: 1,
                spaceBetween: 20,
            },
            // when window width is >= 768px
            768: {
                slidesPerView: 1.5,
                spaceBetween: 30,
            },
            // when window width is >= 1024px
            1024: {
                slidesPerView: 2,
                spaceBetween: 40,
            },
            // when window width is >= 1280px
            1280: {
                slidesPerView: 2.5,
                spaceBetween: 50,
            }
        },
        on: {
            init: function() {
                console.log('Features Swiper initialized successfully');
                // Make the swiper visible once it's properly initialized
                const featuresSwiperElement = document.querySelector('#features-swiper');
                if (featuresSwiperElement) {
                    featuresSwiperElement.style.visibility = 'visible';
                    // Force a reflow to ensure proper layout
                    featuresSwiperElement.offsetHeight;
                }
            },
            // Ensure proper layout after all slides are loaded
            imagesReady: function() {
                this.update();
            }
        }
    });

    // Add hover effect to pause autoplay
    const featuresSwiperContainer = document.querySelector('#features-swiper');
    if (featuresSwiperContainer) {
        featuresSwiperContainer.addEventListener('mouseenter', function() {
            featuresSwiper.autoplay.stop();
        });
        featuresSwiperContainer.addEventListener('mouseleave', function() {
            featuresSwiper.autoplay.start();
        });
    }

    // Enhanced Intersection Observer for animations with staggered effect
    const observerOptions = {
        threshold: 0.15,
        rootMargin: '0px 0px -100px 0px'
    };

    const observerCallback = (entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                // Add staggered delay based on index
                setTimeout(() => {
                    entry.target.classList.add('animate-fade-in');
                }, index * 150); // 150ms staggered delay
                observer.unobserve(entry.target);
            }
        });
    };

    const observer = new IntersectionObserver(observerCallback, observerOptions);

    // Observe sections and their children for more granular animations
    document.querySelectorAll('.homepage section').forEach(section => {
        if (!section.classList.contains('animate-fade-in')) {
            observer.observe(section);
        }

        // Also observe key elements within sections for staggered animations
        section.querySelectorAll('.feature-card, .benefit-card, .workflow-step, .highlight-card').forEach((element, index) => {
            if (!element.classList.contains('animate-fade-in')) {
                observer.observe(element);
            }
        });
    });
});
