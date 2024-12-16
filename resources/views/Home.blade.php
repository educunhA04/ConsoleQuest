@extends('layouts.app')

@section('navigation')
<nav class="main-nav">
    <a href="{{ url('/filtered') }}?query=Home">Home</a>
    <a href="{{ url('/filtered') }}?query=Controllers">Controllers</a>
    <a href="{{ url('/filtered') }}?query=Games">Games</a>
    <a href="{{ url('/filtered') }}?query=Consoles">Consoles</a>
</nav>

@endsection

@section('filters')
<div class="filters-section" id="filtersSection" style="display: none;">
    <form id="filterForm" method="GET" action="{{ url('/filtered') }}">
        <!-- Search Query (optional, persists during filtering) -->
        <input type="hidden" name="query" value="{{ request('query') }}">

        <!-- Price Range -->
        <label for="min_price">Min Price:</label>
        <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}">

        <label for="max_price">Max Price:</label>
        <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}">

        <!-- Discount Filter -->
        <label>
        Only Discounted Items
            <input type="checkbox" name="discount_only" value="1" {{ request('discount_only') ? 'checked' : '' }}>
            
        </label>

        <!-- Type Filter Dropdown (Hardcoded) -->
        <label for="type_id">Type:</label>
        <select name="type_id" id="type_id">
            <option value="">All Types</option>
            <option value="1" {{ request('type_id') == 1 ? 'selected' : '' }}>PS5 Console</option>
            <option value="2" {{ request('type_id') == 2 ? 'selected' : '' }}>Football</option>
            <option value="3" {{ request('type_id') == 3 ? 'selected' : '' }}>MOBA</option>
            <option value="4" {{ request('type_id') == 4 ? 'selected' : '' }}>PS5 Wireless Controller</option>
            <option value="5" {{ request('type_id') == 5 ? 'selected' : '' }}>Xbox Console</option>
            <option value="6" {{ request('type_id') == 6 ? 'selected' : '' }}>Adventure RPG</option>
            <option value="7" {{ request('type_id') == 7 ? 'selected' : '' }}>Racing</option>
            <option value="8" {{ request('type_id') == 8 ? 'selected' : '' }}>Nintendo Console</option>
            <option value="9" {{ request('type_id') == 9 ? 'selected' : '' }}>Nintendo Controller</option>
            <option value="10" {{ request('type_id') == 10 ? 'selected' : '' }}>Action RPG</option>
            <option value="11" {{ request('type_id') == 11 ? 'selected' : '' }}>Action Adventure</option>
            <option value="12" {{ request('type_id') == 12 ? 'selected' : '' }}>Horror RPG</option>
            <option value="13" {{ request('type_id') == 13 ? 'selected' : '' }}>Battle Royal</option>
        </select>

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
                <button 
                    class="fas fa-heart fav-icon" 
                    aria-label="Add to wishlist"
                    onclick="addToWishlist({{ $product->id }})">
                </button>

                <button 
                    class="fas fa-shopping-cart cart-icon" 
                    aria-label="Add to cart"
                    onclick="addToCart({{ $product->id }}, 1)">
                </button>
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

<div class="d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
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