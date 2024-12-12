function openOrderDetailsFromElement(element) {
    // Read the data-* attributes from the clicked element
    const trackingId = element.getAttribute('data-tracking');
    const date = element.getAttribute('data-date');
    const status = element.getAttribute('data-status');
    const total = parseFloat(element.getAttribute('data-total')).toFixed(2);
    const products = JSON.parse(element.getAttribute('data-products'));
    const images = JSON.parse(element.getAttribute('data-images'));
    const productPages = JSON.parse(element.getAttribute('data-product-page'));

    // Call the function to display the modal with details
    openOrderDetails(trackingId, date, status, total, products, images, productPages);
}

function openOrderDetails(trackingId, date, status, total, products, images, productPages) {
    // Update modal content dynamically
    document.getElementById('modalTrackingId').textContent = trackingId;
    document.getElementById('modalDate').textContent = date;
    document.getElementById('modalStatus').textContent = status;
    document.getElementById('modalTotal').textContent = `€${parseFloat(total).toFixed(2)}`;

    // Clear and update the products list
    const productsList = document.getElementById('modalProducts');
    productsList.innerHTML = ''; // Clear previous list
    products.forEach((product, index) => {
        const listItem = document.createElement('li');
        listItem.className = 'product-item'; // Add a class for styling
    
        // Create a link to the product page
        const link = document.createElement('a');
        link.href = productPages[index].url;
        link.style.display = 'flex'; // Flex container for image + name
        link.style.alignItems = 'center'; // Align content vertically
        link.style.textDecoration = 'none';
        link.style.color = 'inherit';
    
        // Add product image
        const img = document.createElement('img');
        img.src = images[index];
        img.alt = product.name;
        img.style.width = '50px';
        img.style.height = '50px';
        img.style.marginRight = '10px';
        link.appendChild(img);
    
        // Add product name to the link
        const nameSpan = document.createElement('span');
        nameSpan.textContent = product.name;
        nameSpan.style.fontWeight = 'bold';
        link.appendChild(nameSpan);
    
        // Add the link to the list item
        listItem.appendChild(link);
    
       // Create a container for the product details
        const detailsContainer = document.createElement('div');
        detailsContainer.style.display = 'flex';
        detailsContainer.style.flexDirection = 'column';
        detailsContainer.style.marginLeft = '10px'; // Space after link
    
        // Add product quantity
        const quantitySpan = document.createElement('span');
        quantitySpan.textContent = `Quantity: ${product.quantity}`;
        detailsContainer.appendChild(quantitySpan);
    
        // Add product price
        const priceSpan = document.createElement('span');
        priceSpan.textContent = `Price: €${parseFloat(product.price).toFixed(2)}`;
        detailsContainer.appendChild(priceSpan);
    
        // Calculate and add total value
        const totalValue = parseFloat(product.quantity * product.price).toFixed(2);
        const totalSpan = document.createElement('span');
        totalSpan.textContent = `Total: €${totalValue}`;
        detailsContainer.appendChild(totalSpan);
    
        // Add the details container to the list item
        listItem.appendChild(detailsContainer);
    
        // Add the list item to the products list
        productsList.appendChild(listItem);
    });
    

    // Display the modal
    document.getElementById('orderModal').style.display = 'block';
}

function closeOrderDetails() {
    // Hide the modal
    document.getElementById('orderModal').style.display = 'none';
}
