@extends('layouts.admin')

@section('content')
    <div class="admin-create-form-container">
        <h1>Create New Product</h1>
        
        <form action="{{ route('admin.storeProduct') }}" method="POST" class="admin-create-form" enctype="multipart/form-data">
            @csrf

            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" required>

            <label for="category">Category</label>
            <select id="category" name="category_id" required>
                    <option value="1">Consoles</option>
                    <option value="2">Video Games</option>
                    <option value="3">Controllers</option>

            </select>

            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>

            <label for="price">Price</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" min="0" required>

            <label for="Discount">Discount</label>
            <input type="number" id="discount" name="discount" min="0" max = "100">

            <label for="image">Image</label>
            <input type="file" id="image" name="image" required>

            <button type="submit" class="admin-submit-btn">Create Product</button>
        </form>
    </div>
@endsection
