document.addEventListener("DOMContentLoaded", () => {
    const notificationContainer = document.getElementById("notification-container");

    // Function to create and display a notification
    function showNotification(message, isError = false) {
        const notification = document.createElement("div");
        notification.className = "notification";
        if (isError) {
            notification.classList.add("error");
        }
        notification.textContent = message;

        // Add the notification to the container
        notificationContainer.appendChild(notification);

        // Remove the notification after the animation ends
        setTimeout(() => {
            notificationContainer.removeChild(notification);
        }, 3000); // Matches the animation duration (3s)
    }

    // Add to Wishlist function
    async function addToWishlist(productId) {
        try {
            console.log("Sending request to:", wishlistAddUrl);
            const response = await fetch(wishlistAddUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId })
            });

            const textResponse = await response.text();
            console.log('Raw server response:', textResponse);

            try {
                const data = JSON.parse(textResponse);
                if (response.ok) {
                    showNotification(data.message || 'Added to wishlist successfully!');
                } else {
                    showNotification(data.error || 'Failed to add to wishlist.', true);
                }
            } catch (parseError) {
                console.error('JSON Parsing Error:', parseError, textResponse);
                showNotification('Unexpected response from server.', true);
            }
        } catch (error) {
            console.error('Error with AJAX request:', error);
            showNotification('An error occurred while adding to wishlist.', true);
        }
    }

    // Add to Cart function
    async function addToCart(productId, quantity) {
        try {
            console.log("Sending request to:", cartAddUrl);
            const response = await fetch(cartAddUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId, quantity })
            });

            const textResponse = await response.text();
            console.log('Raw server response:', textResponse);

            try {
                const data = JSON.parse(textResponse);
                if (response.ok) {
                    showNotification(data.message || 'Added to cart successfully!');
                } else {
                    showNotification(data.error || 'Failed to add to cart.', true);
                }
            } catch (parseError) {
                console.error('JSON Parsing Error:', parseError, textResponse);
                showNotification('Unexpected response from server.', true);
            }
        } catch (error) {
            console.error('Error with AJAX request:', error);
            showNotification('An error occurred while adding to cart.', true);
        }
    }

    const pusher = new Pusher('dd377fb9b05634e11fa2', {
        cluster: 'eu',
        encrypted: true,
    });
    
    const channel = pusher.subscribe('Console-Quest');
    channel.bind('notification-pusher', async function (data) {
        // Show the temporary notification (toast) naao sei porque é que se chama assim nao perguntem
        showNotification(data.message);
    
        // Fetch the updated notifications from the server
        try {
            const response = await fetch('/notifications', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            });
    
            if (response.ok) {
                const html = await response.text();
    
                // Update the notifications-section in the DOM
                const notificationsSection = document.querySelector('.notifications-section');
                if (notificationsSection) {
                    notificationsSection.innerHTML = html;
                }
            } else {
                console.error('Failed to fetch updated notifications:', response.statusText);
            }
        } catch (error) {
            console.error('Error fetching updated notifications:', error);
        }
    });
    
    
    window.addToWishlist = addToWishlist;
    window.addToCart = addToCart;
});
