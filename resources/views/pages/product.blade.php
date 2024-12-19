@extends('layouts.app')
    @section('content')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> / 
        @php
            $categoryRoute = match($product->category->type) {
                'Controllers' => route('home.controllers'),
                'Video Games' => route('home.games'),
                'Consoles' => route('home.consoles'),
                default => '#'
            };
        @endphp
        <a href="{{ $categoryRoute }}">{{ $product->category->type ?? 'Categoria Desconhecida' }}</a> / 
        {{ $product->name }} <!-- Dynamically show category and product name -->
    </div>

    <div class="product-page">
        <div class="product-info">
            <h1 class="product-name">{{ $product->name }}</h1> <!-- Product name -->
            <h2 class="type">{{ $product->type->name }}</h2> 

            <div class="rating">
                @php
                    $fullStars = floor($product->reviews->avg('rating'));
                    $halfStar = ($product->reviews->avg('rating') - $fullStars) >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                @endphp

                {{-- Display full stars --}}
                @for ($i = 0; $i < $fullStars; $i++)
                    ★
                @endfor

                {{-- Display half star --}}
                @if ($halfStar)
                    ☆
                @endif

                {{-- Display empty stars --}}
                @for ($i = 0; $i < $emptyStars; $i++)
                    ☆
                @endfor

            </div>

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

        <div class="product-details">
            <div class="price-and-heart">
                <div class="price">{{ number_format($product->price, 2) }} €</div> <!-- Product price -->
                <button 
                id = "wishlist-heart"
                    class="fas fa-heart fav-icon" 
                    aria-label="Add to wishlist"
                    onclick="addToWishlist({{ $product->id }})">
                </button>

            <p class="description">{{ $product->description }}</p> <!-- Product description -->
            <button 
                    class="add-to-cart-btn"
                    aria-label="Add to cart"
                    onclick="addToCart({{ $product->id }}, 1)">
                    Add to Cart
            </button>
        </div>
        
    </div>  
    <div class="reviews">
            <h3>Reviews</h3>

            {{-- Exibição de Avaliações --}}
            @if($product->reviews->isEmpty())
                <p>This product has no ratings yet. Be the first one to rate it!</p>
                <p></p>
            @else
                <div class="average-rating">
                    <strong>Average:</strong> {{ number_format($product->reviews->avg('rating'), 1) }}/5 ★
                </div>
                @foreach($product->reviews as $review)
                <div class="review" id="review-{{ $review->id }}" data-is-owner="{{ $review->user_id === auth()->id() ? 'true' : 'false' }}">
                    <strong>{{ $review->user->name }}</strong> {{ $review->rating }}/5 ★
                    <p>{{ $review->description }}</p>

                    {{-- Botões de Editar/Excluir --}}
                    @if ($review->user_id === auth()->id())
                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn" style="display: none;">Delete</button>
                        </form>
                        <button class="edit-btn" onclick="showEditForm({{ $review->id }})" style="display: none;">Edit</button>

                    @endif
                    <button class="report-btn" style="display: none;" onclick="showReportForm({{ $review->id }})">Report</button>


                    {{-- Formulário de edição --}}
                    @if ($review->user_id === auth()->id())
                        <form action="{{ route('reviews.update', $review->id) }}" method="POST" class="edit-form" style="display: none;">
                            @csrf
                            @method('PUT')
                            <div>
                                <label for="rating-{{ $review->id }}">Rating:</label>
                                <input type="number" id="rating-{{ $review->id }}" name="rating" value="{{ $review->rating }}" min="0" max="5" required>
                            </div>
                            <div>
                                <label for="description-{{ $review->id }}">Description:</label>
                                <textarea id="description-{{ $review->id }}" name="description" required>{{ $review->description }}</textarea>
                            </div>
                            <button type="submit" class="save-btn">Save</button>
                            <button type="button" class="cancel-btn" onclick="hideEditForm({{ $review->id }})">Cancel</button>
                        </form>
                    @endif
                    @if ($review->user_id !== auth()->id())

                      <form action="{{ route('reviews.report', $review->id) }}" method="POST" class="report-form" style="display: none;">
                        @csrf
                        <h4>Report Review</h4>
                        <label for="reason-{{ $review->id }}">Reason:</label>
                        <input type="text" id="reason-{{ $review->id }}" name="reason" required>
                        <label for="description-{{ $review->id }}">Description:</label>
                        <textarea id="description-{{ $review->id }}" name="description"></textarea>
                        <button type="submit" class="submit-report-btn">Submit Report</button>
                        <button type="button" class="cancel-report-btn">Cancel</button>
                    </form>
                    @endif

                </div>
                @endforeach

            @endif

            {{-- Formulário de Adicionar Avaliação --}}
            @auth
            @if($hasPurchased)
                @if(!$existingReview)
                    {{-- Formulário para adicionar nova avaliação --}}
                    <div class="review-form">
                        <h4>Add your rating:</h4>
                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div>
                                <label for="rating">Rating:</label>
                                <input type="number" id="rating" name="rating" min="0" max="5" required>
                            </div>
                            <div>
                                <label for="description">Description:</label>
                                <textarea id="description" name="description" required></textarea>
                            </div>
                            <button type="submit" class="submit-btn">Send Rating</button>
                        </form>
                    </div>
                @else
                    <p>You already rated this product. Edit or delete your existing rating.</p>
                @endif
            @else
                <p>You need to buy this product before rating it.</p>
            @endif


            @endauth

            {{-- Mensagem para usuários não autenticados --}}
            @guest
                <p>Please login to add your rating.</p>
            @endguest
        </div>
@endsection