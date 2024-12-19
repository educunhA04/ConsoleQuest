@extends('layouts.admin')

@section('content')
<div class="admin-product-details">
    <div class="admin-title-box">
        <div class="admin-title-product">Edit Product Details</div>
    </div>
    
    <div class="admin-product-container">
    <!-- First column: Image -->
    <div class="admin-product-image">
        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($product->images as $key => $image)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $image->url) }}" class="d-block w-100" alt="{{ $product->name }}">
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <form action="{{ route('admin.changeProduct') }}" method="POST" class="admin-product-form">
    @csrf
            <div class="form-column-middle">
        
            <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">

            <!-- Editable Fields -->
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <option value="1" {{ $product->category_id == 1 ? 'selected' : '' }}>Consoles</option>
                    <option value="2" {{ $product->category_id == 2 ? 'selected' : '' }}>Video Games</option>
                    <option value="3" {{ $product->category_id == 3 ? 'selected' : '' }}>Controllers</option>
                </select>
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type_id" class="form-control" required>
                    <option value="">Select a Type</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ (old('type_id', $product->type_id) == $type->id) ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('type_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
    </div>

    <!-- Third column: Right form -->
    <div class="form-column-right">
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="form-control" value="{{ old('quantity', $product->quantity) }}" min="0" required>
                @error('quantity')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" class="form-control" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="discount">Discount (%)</label>
                <input type="number" id="discount" name="discount" class="form-control" value="{{ old('discount', $product->discount_percent) }}" step="0.01" min="0" max="100">
                @error('discount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary update-button">Update Product</button>
    </div>
    <button type="submit" class="btn btn-primary update-button">Update Product</button>
    </form>

</div>

@endsection
