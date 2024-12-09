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
    document.getElementById('modalTotal').textContent = `€${total}`;

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

    // Add cancel button if order is processing
    const modalFooter = document.getElementById('modalFooter');
    modalFooter.innerHTML = ''; // Clear previous buttons
    if (status.toLowerCase() === 'processing') {
        const cancelButton = document.createElement('button');
        cancelButton.className = 'cancel-order-btn';
        cancelButton.textContent = 'Cancel Order';
        cancelButton.onclick = () => cancelOrder(trackingId);
        modalFooter.appendChild(cancelButton);
    }

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

function cancelOrder(orderId) {
    if (confirm('Are you sure you want to cancel this order?')) {
        fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => {
            if (response.ok) {
                alert('Order cancelled successfully.');
                window.location.reload();
            } else {
                response.json().then(data => {
                    alert(data.error || 'An error occurred while cancelling the order.');
                });
            }
        }).catch(error => {
            alert('An unexpected error occurred.');
            console.error(error);
        });
    }
}
