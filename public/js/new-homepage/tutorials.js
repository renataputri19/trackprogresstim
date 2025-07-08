document.addEventListener('DOMContentLoaded', function() {
    // Force light mode
    document.documentElement.classList.remove("dark");
    document.documentElement.classList.add("light");
    document.body.classList.add("light");
    document.body.classList.remove("dark");
    localStorage.setItem("theme", "light");

    const categoryButtons = document.querySelectorAll('.category-button');
    const searchInput = document.getElementById('search-tutorials');
    const tutorialCards = document.querySelectorAll('.tutorial-card');
    const noResults = document.getElementById('no-results');

    // Filter function
    function filterTutorials() {
        const activeCategory = document.querySelector('.category-button.active').dataset.category;
        const searchTerm = searchInput.value.toLowerCase();
        let visibleCount = 0;

        tutorialCards.forEach(card => {
            const category = card.dataset.category;
            const title = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('p').textContent.toLowerCase();

            const matchesCategory = activeCategory === 'All' || category === activeCategory;
            const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);

            if (matchesCategory && matchesSearch) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide no results message
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }

    // Category button click handler
    categoryButtons.forEach(button => {
        button.addEventListener('click', () => {
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            filterTutorials();
        });
    });

    // Search input handler
    searchInput.addEventListener('input', filterTutorials);
});
