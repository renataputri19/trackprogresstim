document.addEventListener('DOMContentLoaded', function() {
    const toggleColumnsButton = document.getElementById('toggle-columns');
    const extraColumns = document.querySelectorAll('.extra-columns');
    let columnsVisible = false;

    toggleColumnsButton.addEventListener('click', function() {
        columnsVisible = !columnsVisible;
        extraColumns.forEach(column => {
            column.style.display = columnsVisible ? 'table-cell' : 'none';
        });
        toggleColumnsButton.textContent = columnsVisible ? 'Hide Extra Columns' : 'Show All Columns';
    });

    // Hide extra columns by default
    extraColumns.forEach(column => {
        column.style.display = 'none';
    });
});
