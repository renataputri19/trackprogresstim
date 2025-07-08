document.addEventListener('DOMContentLoaded', function() {
    // Force light mode
    document.documentElement.classList.remove("dark");
    document.documentElement.classList.add("light");
    document.body.classList.add("light");
    document.body.classList.remove("dark");
    localStorage.setItem("theme", "light");
    
    const searchInput = document.getElementById('search-docs');
    const docCards = document.querySelectorAll('.doc-card');
    const searchResults = document.getElementById('search-results');
    const resultsContainer = document.getElementById('results-container');
    const documentationContent = document.getElementById('documentation-content');
    const noResults = document.getElementById('no-results');
    
    // Search function
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        if (searchTerm === '') {
            // Show original content
            searchResults.classList.add('hidden');
            documentationContent.classList.remove('hidden');
            noResults.classList.add('hidden');
            return;
        }
        
        // Clear previous results
        resultsContainer.innerHTML = '';
        let matchCount = 0;
        
        // Search through all doc cards
        docCards.forEach(card => {
            const title = card.dataset.title.toLowerCase();
            const description = card.dataset.description.toLowerCase();
            
            if (title.includes(searchTerm) || description.includes(searchTerm)) {
                // Clone the card and add to results
                const clone = card.cloneNode(true);
                resultsContainer.appendChild(clone);
                matchCount++;
            }
        });
        
        // Show/hide appropriate sections
        if (matchCount > 0) {
            searchResults.classList.remove('hidden');
            documentationContent.classList.add('hidden');
            noResults.classList.add('hidden');
        } else {
            searchResults.classList.add('hidden');
            documentationContent.classList.add('hidden');
            noResults.classList.remove('hidden');
        }
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
});
