@extends('layouts.admin')

@section('content')
<div class="admin-product-details">
    <h1>Product Details</h1>
    
    <div class="admin-product-container">
        <div class="admin-product-image">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
        </div>
        
        <div class="admin-product-info">
            <h2>{{ $product->name }}</h2>
            <p><strong>Category:</strong> {{ $product->category->type }}</p>
            <p><strong>Description:</strong> {{ $product->description }}</p>
            <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
            <p><strong>Price:</strong> ${{ $product->price }}</p>
            <p><strong>Discount:</strong> {{ $product->discount_percent }}%</p>

            <form action="{{ route('admin.changeProduct', ['id' => $product->id]) }}" method="POST" class="admin-product-form">
                @csrf
                @method('POST')

                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" value="{{ $product->quantity }}" min="0" required>
                <label for="quantity">Discount</label>
                <input type="number" id="discount" name="discount" value="{{ $product->discount_percent }}" min="0" max = "100" required>
                <input type="hidden" id = "product_id" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="update-button">Update Product</button>
            </form>
        </div>
    </div>
</div>
@endsection
