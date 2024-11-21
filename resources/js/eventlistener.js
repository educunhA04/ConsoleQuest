document.addEventListener('DOMContentLoaded', () => {
    const cartRows = document.querySelectorAll('.product-row');

    cartRows.forEach(row => {
        const decreaseButton = row.querySelector('.btn-decrease');
        const increaseButton = row.querySelector('.btn-increase');
        const quantityInput = row.querySelector('.quantity-input');

        // Event listener for decrease button
        decreaseButton.addEventListener('click', () => {
            let currentValue = parseInt(quantityInput.value, 10);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updateTotal();
            }
        });

        // Event listener for increase button
        increaseButton.addEventListener('click', () => {
            let currentValue = parseInt(quantityInput.value, 10);
            quantityInput.value = currentValue + 1;
            updateTotal();
        });

        // Prevent invalid input
        quantityInput.addEventListener('input', () => {
            if (quantityInput.value < 1) {
                quantityInput.value = 1;
            }
            updateTotal();
        });
    });

    // Function to update the total price
    function updateTotal() {
        let total = 0;
        cartRows.forEach(row => {
            const quantity = parseInt(row.querySelector('.quantity-input').value, 10);
            const price = parseFloat(row.querySelector('.product-price').textContent.replace('€', ''));
            total += quantity * price;
        });

        document.querySelector('.summary-total span:last-child').textContent = `${total.toFixed(2)}€`;
    }
});
