// KMS JavaScript functionality - Simplified version
document.addEventListener('DOMContentLoaded', function() {
    console.log('KMS JavaScript loading...');

    try {
        // Initialize fade-in animations
        const fadeElements = document.querySelectorAll('.kms-fade-in');
        if (fadeElements.length > 0) {
            fadeElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.1}s`;
            });
        }

        // Simple form validation
        const forms = document.querySelectorAll('form');
        if (forms.length > 0) {
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('border-red-500');
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        alert('Mohon lengkapi semua field yang wajib diisi.');
                    }
                });
            });
        }

        // Clear validation errors on input
        const inputs = document.querySelectorAll('input, textarea, select');
        if (inputs.length > 0) {
            inputs.forEach(input => {
                input.addEventListener('input', function() {
            this.classList.remove('border-red-500');
            const existingError = this.parentNode.querySelector('.validation-error');
            if (existingError) {
                existingError.remove();
            }
        });
    });

    // Smooth scroll for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Loading state for buttons
    const buttons = document.querySelectorAll('button[type="submit"]');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            if (form && form.checkValidity()) {
                this.disabled = true;
                this.innerHTML = `
                    <div class="kms-loading"></div>
                    <span>Memproses...</span>
                `;

                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    this.disabled = false;
                    this.innerHTML = this.dataset.originalText || 'Submit';
                }, 10000);
            }
        });

        // Store original text
        button.dataset.originalText = button.innerHTML;
    });

    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });

    // Back to top button
    const backToTopButton = document.createElement('button');
    backToTopButton.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m18 15-6-6-6 6"/>
        </svg>
    `;
    backToTopButton.className = 'fixed bottom-4 right-4 bg-teal-600 text-white p-3 rounded-full shadow-lg hover:bg-teal-700 transition-all duration-300 opacity-0 invisible';
    backToTopButton.style.zIndex = '1000';

    document.body.appendChild(backToTopButton);

    // Show/hide back to top button
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopButton.classList.remove('opacity-0', 'invisible');
        } else {
            backToTopButton.classList.add('opacity-0', 'invisible');
        }
    });

    // Back to top functionality
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Accordion functionality
    function initializeAccordion() {
        const accordionHeaders = document.querySelectorAll('.kms-accordion-header');

        accordionHeaders.forEach(header => {
            header.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const icon = this.querySelector('.kms-accordion-icon');
                const isActive = this.classList.contains('active');

                // Toggle current accordion
                if (isActive) {
                    // Close current accordion
                    this.classList.remove('active');
                    content.classList.remove('active');
                    this.setAttribute('aria-expanded', 'false');

                    // Smooth collapse animation
                    content.style.maxHeight = content.scrollHeight + 'px';
                    setTimeout(() => {
                        content.style.maxHeight = '0';
                    }, 10);
                } else {
                    // Open current accordion
                    this.classList.add('active');
                    content.classList.add('active');
                    this.setAttribute('aria-expanded', 'true');

                    // Smooth expand animation
                    content.style.maxHeight = '0';
                    setTimeout(() => {
                        content.style.maxHeight = content.scrollHeight + 'px';
                    }, 10);

                    // Reset max-height after animation completes
                    setTimeout(() => {
                        if (content.classList.contains('active')) {
                            content.style.maxHeight = 'none';
                        }
                    }, 400);
                }

                // Add fade-in animation to accordion body content
                const accordionBody = content.querySelector('.kms-accordion-body');
                if (accordionBody && !isActive) {
                    accordionBody.style.opacity = '0';
                    setTimeout(() => {
                        accordionBody.style.transition = 'opacity 0.3s ease-in';
                        accordionBody.style.opacity = '1';
                    }, 200);
                }
            });
        });
    }

    // Initialize accordion if accordion elements exist
    if (document.querySelector('.kms-accordion')) {
        initializeAccordion();
    }

    // Keyboard accessibility for accordion
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            const target = e.target;
            if (target.classList.contains('kms-accordion-header')) {
                e.preventDefault();
                target.click();
            }
        }
    });

    // Touch support for mobile devices
    let touchStartY = 0;
    let touchEndY = 0;

    document.addEventListener('touchstart', function(e) {
        touchStartY = e.changedTouches[0].screenY;
    });

    document.addEventListener('touchend', function(e) {
        touchEndY = e.changedTouches[0].screenY;
        const target = e.target.closest('.kms-accordion-header');

        if (target && Math.abs(touchEndY - touchStartY) < 10) {
            // This was a tap, not a scroll
            target.click();
        }
    });

    console.log('KMS JavaScript initialized successfully');
});