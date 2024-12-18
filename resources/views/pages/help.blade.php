@extends('layouts.app')

@section('content')

<div class="static-page">
    <div class="staticpage-header">
        <h1>HELP PAGE (?)</h1>
    </div>
    <div class="staticpage-content">
        <div class="staticpage-item">
            <h2>Homepage</h2>
            <p>
                The homepage is the central hub of Console Quest. Here, you can browse featured products such as consoles, controllers, and games. 
                Each product card displays the name, price, and availability. Clicking on a product card takes you to its detailed page where you can learn more about it and make a purchase.
            </p>
            <p>
                Use the navigation bar at the top of the page to explore the main sections of the website:
                <ul>
                    <li><strong>Home:</strong> Returns you to the homepage where you can browse products.</li>
                    <li><strong>Controllers:</strong> View all controllers available for purchase.</li>
                    <li><strong>Games:</strong> Explore a wide selection of video games.</li>
                    <li><strong>Consoles:</strong> Discover the latest gaming consoles.</li>
                </ul>
            </p>
            <p>
                When you hover over a product with your mouse, additional options will appear, allowing you to:
                <ul>
                    <li><strong>Add the product to your shopping cart</strong> for purchase.</li>
                    <li><strong>Add the product to your wishlist</strong> for future reference.</li>
                </ul>
            </p>
            <p>
                The search bar at the top allows you to find specific products by entering keywords. Additionally, the icons on the top-right corner allow you to:
                <ul>
                    <li>View your <strong>wishlist</strong>.</li>
                    <li>Access your <strong>shopping cart</strong>.</li>
                    <li>Manage your <strong>account settings</strong> or log out.</li>
                </ul>
            </p>
        </div>
        
        <div class="staticpage-item">
            <h2>Profile Page</h2>
            <p>
                The Profile Page allows users to manage their personal information and track their orders. Here's a breakdown of the key features:
            </p>
            <ul>
                <li><strong>Profile Information:</strong> Displays your name, username, and email address. To update your details, click the <strong>Edit Profile</strong> button.</li>
                <li><strong>My Orders:</strong> A list of your recent orders with details such as:
                    <ul>
                        <li><strong>Tracking ID:</strong> The unique identifier for your order.</li>
                        <li><strong>Date:</strong> The date the order was placed.</li>
                        <li><strong>Status:</strong> The current status of the order (e.g., Processing, Shipped).</li>
                    </ul>
                </li>
                <li><strong>Logout:</strong> Click the <strong>Logout</strong> button to securely log out of your account.</li>
            </ul>
            <p>
                If you encounter any issues or need assistance, feel free to visit the <a href="help">Help Page</a> or contact our support team.
            </p>
        </div>

        <div class="staticpage-item">
            <h2>Product Details Page</h2>
            <p>
                The Product Details Page provides in-depth information about a specific product, helping you make informed purchase decisions. Here's what you can find:
            </p>
            <ul>
                <li><strong>Product Name:</strong> The title of the product.</li>
                <li><strong>Images:</strong> High-quality images to showcase the product from different angles.</li>
                <li><strong>Description:</strong> A detailed explanation of the product's features and benefits.</li>
                <li><strong>Price:</strong> The current price of the product.</li>
                <li><strong>Availability:</strong> Shows whether the product is in stock or sold out.</li>
                <li><strong>Reviews and Ratings:</strong> User-generated reviews and ratings to help evaluate the product.</li>
            </ul>
            <p>
                On this page, you can:
            </p>
            <ul>
                <li><strong>Add the product to your shopping cart</strong> for checkout.</li>
                <li><strong>Add the product to your wishlist</strong> to save it for later.</li>
            </ul>
            <p>
                Scroll down to view related products or recommendations based on your browsing history.
            </p>
        </div>

        <div class="staticpage-item">
            <h2>Shopping Cart</h2>
            <p>
                The Shopping Cart page is designed to provide an overview of the items you intend to purchase and streamline the checkout process. Here's a guide to the features:
            </p>
            <ul>
                <li><strong>Viewing Items:</strong> All the products added to your shopping cart are displayed here. Each item includes:
                    <ul>
                        <li>Product Name</li>
                        <li>Thumbnail Image</li>
                        <li>Price</li>
                        <li>Quantity</li>
                        <li>Subtotal (Price x Quantity)</li>
                    </ul>
                </li>
                <li><strong>Editing Cart Contents:</strong> You can update your cart by:
                    <ul>
                        <li>Adjusting the quantity of items directly on the page. The subtotal updates dynamically.</li>
                        <li>Removing items from the cart by clicking the <strong>Remove</strong> button next to each product.</li>
                    </ul>
                </li>
                <li><strong>Cart Summary:</strong> The summary section provides a breakdown of:
                    <ul>
                        <li><strong>Subtotal:</strong> Total of all individual item subtotals.</li>
                        <li><strong>Total:</strong> The final amount payable.</li>
                    </ul>
                </li>
                <li><strong>Proceed to Checkout:</strong> Click the <strong>Checkout</strong> button to proceed to the payment and shipping page. If your cart is empty, you will see a message encouraging you to browse products and add items to your cart.</li>
                <li><strong>Additional Features:</strong>
                    <ul>
                        <li>If you are not logged in, you will be prompted to log in or create an account to complete the checkout process.</li>
                        <li>Related product recommendations may be displayed to help you discover more items.</li>
                        <li>A link to your wishlist is provided, allowing you to move items from your cart to your wishlist for future reference.</li>
                    </ul>
                </li>
            </ul>
            <p>
                The Shopping Cart page is optimized to ensure a seamless shopping experience, with clear information and easy-to-use controls.
            </p>
        </div>

        <div class="staticpage-item">
            <h2>Checkout</h2>
            <p>
                The Checkout page is the final step in completing your purchase. Here’s a guide to help you navigate through it:
            </p>
            <ul>
                <li><strong>Order Summary:</strong>
                    <ul>
                        <li>Displays the items in your order, including:
                            <ul>
                                <li>Product Name</li>
                                <li>Thumbnail Image</li>
                                <li>Unit Price</li>
                                <li>Quantity</li>
                                <li>Total Price for each item</li>
                            </ul>
                        </li>
                        <li>The overall <strong>Total Amount</strong> is clearly displayed at the bottom.</li>
                    </ul>
                </li>
                <li><strong>Payment Information:</strong>
                    <ul>
                        <li>Fill in the required payment details, including:
                            <ul>
                                <li><strong>NIF:</strong> Taxpayer Identification Number (must have exactly <strong>9 digits</strong>).</li>
                                <li><strong>Card Number:</strong> Enter your <strong>16-digit</strong> credit or debit card number.</li></li>
                                <li><strong>Expiry Date:</strong> Provide the card’s expiration date in the format <em>dd/mm/yyyy</em>.</li>
                                <li><strong>CVV:</strong> Enter the <strong>3-digit</strong> security code on the back of your card.</li></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><strong>Finalize Purchase:</strong>
                    <ul>
                        <li>Click the <strong>Finalize Purchase</strong> button to complete your order.</li>
                        <li>Ensure all information is correct before proceeding to avoid payment issues.</li>
                    </ul>
                </li>
                <li><strong>Additional Information:</strong>
                    <ul>
                        <li>If any required field is missing or invalid, you will receive an error message prompting you to correct the information.</li>
                        <li>Make sure to double-check your order summary before finalizing the purchase.</li>
                        <li>Secure encryption ensures your payment details remain safe.</li>
                    </ul>
                </li>
            </ul>
            <p>
                The Checkout page is designed to provide a smooth and secure way to complete your purchase, ensuring that all your information is properly handled.
            </p>
        </div>
        <div class="staticpage-item">
            <h2>Contact Us</h2>
            <p>You can reach our support team via email at consolequest@gmail.com or through our <a href="{{ route('home.aboutus') }}">About Us</a> page.</p>
        </section>



    </div>
</div>

@endsection
