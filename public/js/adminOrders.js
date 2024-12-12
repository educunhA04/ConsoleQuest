function openOrderDetailsFromElementAdmin(element) {
    // Get data attributes from the clicked order block
    const trackingId = element.getAttribute('data-tracking');
    const date = element.getAttribute('data-date');
    const status = element.getAttribute('data-status');
    const total = parseFloat(element.getAttribute('data-total')).toFixed(2);
    const products = JSON.parse(element.getAttribute('data-products'));

    // Log data for debugging
    console.log({ trackingId, date, status, total, products });

    // Update modal content
    document.getElementById('modalTrackingId').textContent = trackingId;
    document.getElementById('modalDate').textContent = date;
    document.getElementById('modalStatus').textContent = status;

    const productsList = document.getElementById('modalProducts');
    productsList.innerHTML = ''; // Clear previous list

    // Populate products list in modal
    products.forEach((product) => {
        const listItem = document.createElement('li');
        listItem.innerHTML = `
            <img src="${product.image || ''}" alt="${product.name}" style="width: 50px; height: 50px; margin-right: 10px;">
            <span><strong>${product.name}</strong></span>
            <span>Qty: ${product.quantity}</span>
            <span>Price: €${parseFloat(product.price).toFixed(2)}</span>
        `;
        productsList.appendChild(listItem);
    });

    // Display the modal
    document.getElementById('orderModal').style.display = 'block';
}

function closeOrderDetailsAdmin() {
    document.getElementById('orderModal').style.display = 'none';
}

// Ensure event listeners are attached dynamically
document.addEventListener('DOMContentLoaded', () => {
    const orderBlocks = document.querySelectorAll('.order-block');
    orderBlocks.forEach((element) => {
        element.addEventListener('click', () => openOrderDetailsFromElementAdmin(element));
    });
});
