@extends('layouts.app')

@section('navigation')
<nav class="main-nav">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('home.controllers') }}">Controllers</a>
    <a href="{{ route('home.games') }}">Games</a>
    <a href="{{ route('home.consoles') }}">Consoles</a>
</nav>

@endsection

@section('filters')
<div class="filters-section" id="filtersSection" style="display: none;">
    <form id="filterForm" method="POST" action="{{ url('/home/filter') }}">
        @csrf

        <!-- Price Range -->
        <label for="min_price">Min Price</label>
        <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}">

        <label for="max_price">Max Price</label>
        <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}">

        <!-- Discount Checkbox -->
        <label for="discount_only">
            <input type="checkbox" name="discount_only" id="discount_only" {{ request('discount_only') ? 'checked' : '' }}>
            Only items with discounts
        </label>

        <!-- Submit Button -->
        <button type="submit">Apply Filters</button>
    </form>
</div>
@endsection


@section('content')
<h1>Products</h1>

    @if ($products->isEmpty())
        <p>No products found matching your search.</p>
    @else
    <div class="product-grid">
        @foreach ($products as $product)
        <div class="product-container">
            <div class="icon-container">
                <form action="{{ route('wishlist.add') }}" method="POST" class="add-to-wishlist-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button 
                        type="submit" 
                        class="fas fa-heart fav-icon " 
                        aria-label="Add to wishlist">
                    </button>
                </form>
                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1"> 
                    <button type="submit" class="fas fa-shopping-cart cart-icon" aria-label="Add to cart"></button>
                </form>
            </div>
            <a href="{{ route('product.show', ['id' => $product->id]) }}" class="product-link">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-price">
                    @if ($product->discount_percent > 0)
                        <span class="original-price">${{ number_format($product->price, 2) }}</span>
                        <span class="discounted-price">${{ number_format($product->price - ($product->price * $product->discount_percent / 100), 2) }}</span>
                    @else
                        ${{ number_format($product->price, 2) }}
                    @endif
                </div>
                @if ($product->quantity == 0)
                    <div class="sold-out">Sold Out</div>
                @endif
            </a>

        </div>
        @endforeach
    </div>


    @endif
@endsection


@section('scripts')
<script>
    document.getElementById('toggleFiltersButton').addEventListener('click', function() {
        var filtersSection = document.getElementById('filtersSection');
        if (filtersSection.style.display === 'none') {
            filtersSection.style.display = 'block';
            
        } else {
            filtersSection.style.display = 'none';

        }
    });
</script>
@endsection