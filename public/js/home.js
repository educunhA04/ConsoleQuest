document.addEventListener("DOMContentLoaded", () => {
    const filterForm = document.getElementById("filterForm");
    const searchForm = document.getElementById("searchForm"); // Assuming this is your existing search bar form.

    filterForm.addEventListener("change", (e) => {
        e.preventDefault();

        // Combine search and filter inputs
        const formData = new FormData(filterForm);

        // Add the search query if it exists
        const searchQuery = document.querySelector("#query").value;
        if (searchQuery) {
            formData.append("query", searchQuery);
        }

        // Send AJAX request
        fetch('/home/filter', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            const productsContainer = document.getElementById('productsContainer');
            productsContainer.innerHTML = data.html;
        })
        .catch(error => console.error('Error:', error));
    });
});
