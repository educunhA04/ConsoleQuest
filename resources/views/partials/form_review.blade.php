<form action="{{ isset($review) ? route('reviews.update', $review->id) : route('reviews.store') }}" method="POST">
    @csrf
    @if(isset($review))
        @method('PUT')
    @endif
    <input type="hidden" name="product_id" value="{{ $productId }}">
    <div>
        <label for="rating">Rating:</label>
        <input type="number" id="rating" name="rating" value="{{ $review->rating ?? old('rating') }}" min="0" max="5" required>
    </div>
    <div>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required>{{ $review->description ?? old('description') }}</textarea>
    </div>
    <button type="submit">{{ isset($review) ? 'Update Review' : 'Send Review' }}</button>
</form>
