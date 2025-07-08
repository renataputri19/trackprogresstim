// KMS JavaScript functionality - Minimal version to fix loading issues
document.addEventListener('DOMContentLoaded', function() {
    console.log('KMS JavaScript loading...');
    
    try {
        // Simple accordion functionality
        function initializeAccordion() {
            const accordionHeaders = document.querySelectorAll('.kms-accordion-header');
            
            if (accordionHeaders.length > 0) {
                console.log('Found', accordionHeaders.length, 'accordion headers');
                accordionHeaders.forEach(header => {
                    header.addEventListener('click', function() {
                        const content = this.nextElementSibling;
                        const isActive = this.classList.contains('active');

                        // Toggle current accordion
                        if (isActive) {
                            this.classList.remove('active');
                            content.classList.remove('active');
                            this.setAttribute('aria-expanded', 'false');
                            content.style.maxHeight = '0';
                        } else {
                            this.classList.add('active');
                            content.classList.add('active');
                            this.setAttribute('aria-expanded', 'true');
                            content.style.maxHeight = content.scrollHeight + 'px';
                        }
                    });
                });
            }
        }

        // Initialize accordion if accordion elements exist
        if (document.querySelector('.kms-accordion')) {
            initializeAccordion();
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

        console.log('KMS JavaScript initialized successfully');
        
    } catch (error) {
        console.error('Error initializing KMS JavaScript:', error);
    }
});
