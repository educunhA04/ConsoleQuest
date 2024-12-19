// Opens the order details modal
function openOrderDetailsFromElement(element) {
    // Retrieve data-* attributes from the clicked element
    const trackingId = element.getAttribute('data-tracking');
    const date = element.getAttribute('data-date');
    const status = element.getAttribute('data-status');
    const total = parseFloat(element.getAttribute('data-total')).toFixed(2);
    const products = JSON.parse(element.getAttribute('data-products'));
    const images = JSON.parse(element.getAttribute('data-images'));
    const productPages = JSON.parse(element.getAttribute('data-product-page'));

    // Call the function to display the modal with the order details
    openOrderDetails(trackingId, date, status, total, products, images, productPages);
}

// Populates and displays the order details modal
function openOrderDetails(trackingId, date, status, total, products, images, productPages) {
    // Update modal content dynamically
    document.getElementById('modalTrackingId').textContent = trackingId;
    document.getElementById('modalDate').textContent = date;
    document.getElementById('modalStatus').textContent = status;
    document.getElementById('modalTotal').textContent = `€${parseFloat(total).toFixed(2)}`;

    // Clear the previous products list
    const productsList = document.getElementById('modalProducts');
    productsList.innerHTML = '';

    // Populate the products list
    products.forEach((product, index) => {
        const listItem = document.createElement('li');
        listItem.className = 'product-item'; // Add a class for styling

        // Create a flex container for the product details
        const productContainer = document.createElement('div');
        productContainer.style.display = 'flex';
        productContainer.style.alignItems = 'center';
        productContainer.style.marginBottom = '10px';

        // Add product image
        const img = document.createElement('img');
        img.src = images[index];
        img.alt = product.name;
        img.style.width = '50px';
        img.style.height = '50px';
        img.style.marginRight = '15px';
        productContainer.appendChild(img);

        // Create a link for the product name
        const productLink = document.createElement('a');
        productLink.href = productPages[index].url;
        productLink.textContent = product.name;
        productLink.style.fontWeight = 'bold';
        productLink.style.textDecoration = 'none';
        productLink.style.color = 'inherit';
        productContainer.appendChild(productLink);

        // Add the container to the list item
        listItem.appendChild(productContainer);

        // Create a details section
        const detailsContainer = document.createElement('div');
        detailsContainer.style.marginLeft = '65px';

        // Add product quantity
        const quantitySpan = document.createElement('span');
        quantitySpan.textContent = `Quantity: ${product.quantity}`;
        detailsContainer.appendChild(quantitySpan);

        // Add product price
        const priceSpan = document.createElement('span');
        priceSpan.textContent = `Price: €${parseFloat(product.price).toFixed(2)}`;
        detailsContainer.appendChild(priceSpan);

        // Add total price for the product
        const totalSpan = document.createElement('span');
        const totalValue = parseFloat(product.quantity * product.price).toFixed(2);
        totalSpan.textContent = `Total: €${totalValue}`;
        detailsContainer.appendChild(totalSpan);

        // Append details to the list item
        listItem.appendChild(detailsContainer);

        // Append list item to the products list
        productsList.appendChild(listItem);
    });

    // Display the modal
    document.getElementById('orderModal').style.display = 'block';
}

// Closes the order details modal
function closeOrderDetails() {
    document.getElementById('orderModal').style.display = 'none';
}

// Opens the cancel order modal
function openCancelOrderModal(event, orderId) {
    event.stopPropagation(); // Prevent triggering the parent click event
    document.getElementById('cancelOrderForm').action = `/orders/${orderId}/cancel`;
    document.getElementById('cancelOrderModal').style.display = 'block';
}

// Closes the cancel order modal
function closeCancelOrderModal() {
    document.getElementById('cancelOrderModal').style.display = 'none';
}
