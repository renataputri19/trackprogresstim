// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', () => {
    const categoryButtons = document.querySelectorAll('.category-button');
    const searchInput = document.getElementById('search-tutorials');
    const tutorialCards = document.querySelectorAll('.tutorial-card');
    const noResults = document.getElementById('no-results');
    const tutorialsGrid = document.getElementById('tutorials-grid');

    // Initial state
    let currentCategory = 'Semua';
    let searchQuery = '';

    // Function to filter tutorials
    function filterTutorials() {
        tutorialCards.forEach(card => {
            const categoryMatch = currentCategory === 'Semua' || card.getAttribute('data-category') === currentCategory;
            const title = card.getAttribute('data-title').toLowerCase();
            const description = card.getAttribute('data-description').toLowerCase();
            const searchMatch = title.includes(searchQuery.toLowerCase()) || description.includes(searchQuery.toLowerCase());

            if (categoryMatch && searchMatch) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });

        // Show or hide no results message
        const visibleCards = Array.from(tutorialCards).filter(card => card.style.display !== 'none').length;
        noResults.classList.toggle('hidden', visibleCards > 0);
        tutorialsGrid.classList.toggle('hidden', visibleCards === 0);
    }

    // Handle category button clicks
    categoryButtons.forEach(button => {
        button.addEventListener('click', () => {
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            currentCategory = button.getAttribute('data-category');
            filterTutorials();
        });
    });

    // Handle search input changes
    searchInput.addEventListener('input', () => {
        searchQuery = searchInput.value.trim();
        filterTutorials();
    });

    // Initial filter application
    filterTutorials();
});