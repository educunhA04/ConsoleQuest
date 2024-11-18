@extends('layouts.app')
@section('content')
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

@endsection
