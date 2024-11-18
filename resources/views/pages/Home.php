<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Console Quest Products</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="product-grid">
    <?php
    // Placeholder loop for products. Replace this with database fetching logic.
    for ($i = 0; $i < 8; $i++) {
        echo '<div class="product-container">';
        echo '<img src="placeholder.jpg" alt="Product Image" class="product-image">'; // Placeholder for product image
        echo '<div class="product-name">Product Name</div>'; // Placeholder for product name
        echo '<div class="product-price">0.00€</div>'; // Placeholder for product price
        echo '</div>';
    }
    ?>
</div>

</body>
</html>
