document.addEventListener("DOMContentLoaded", () => {
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

            console.log('Response status:', response.status);

            const textResponse = await response.text();
            console.log('Raw server response:', textResponse);

            try {
                const data = JSON.parse(textResponse);
                console.log('Parsed data:', data);
                if (response.ok) {
                    alert(data.message || 'Added to wishlist successfully!');
                } else {
                    alert(data.error || 'Failed to add to wishlist.');
                }
            } catch (parseError) {
                console.error('JSON Parsing Error:', parseError, textResponse);
                alert('Unexpected response from server.');
            }
        } catch (error) {
            console.error('Error with AJAX request:', error);
            alert('An error occurred while adding to wishlist.');
        }
    }

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

            console.log('Response status:', response.status);

            const textResponse = await response.text();
            console.log('Raw server response:', textResponse);

            try {
                const data = JSON.parse(textResponse);
                console.log('Parsed data:', data);
                if (response.ok) {
                    alert(data.message || 'Added to cart successfully!');
                } else {
                    alert(data.error || 'Failed to add to cart.');
                }
            } catch (parseError) {
                console.error('JSON Parsing Error:', parseError, textResponse);
                alert('Unexpected response from server.');
            }
        } catch (error) {
            console.error('Error with AJAX request:', error);
            alert('An error occurred while adding to cart.');
        }
    }

    window.addToWishlist = addToWishlist;
    window.addToCart = addToCart;
});
