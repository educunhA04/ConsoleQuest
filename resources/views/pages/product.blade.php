@extends('layouts.app')
<head>
    <title>Console Quest - {{ $product->name }}</title> <!-- Use the product name for the title -->
    <link rel="stylesheet" href="{{ asset('css/pages/product.css') }}">
</head>
<body>
    @section('content')
    <div class="breadcrumb">
        Home / {{ $product->category->type ?? 'Categoria Desconhecida' }} / {{ $product->name }} <!-- Dynamically show category and product name -->
    </div>
    <div class="product-page">
        <div class="product-info">
            <h1 class="product-name">{{ $product->name }}</h1> <!-- Product name -->
            <div class="rating">
                ★★★★☆ <!-- You can replace this with dynamic ratings if needed -->
            </div>
            <img src="{{ asset('storage/' . $product->image) }}"  alt="{{ $product->name }}" class="product-image"> <!-- Product image -->
        </div>

        <div class="product-details">
            <form action="{{ route('wishlist.add') }}" method="POST" class="add-to-wishlist-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button class="wishlist-icon">♡</button>
            </form>
            <div class="price">{{ number_format($product->price, 2) }} €</div> <!-- Product price -->
            <p class="description">{{ $product->description }}</p> <!-- Product description -->
            <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1"> 
                    <button class="add-to-cart-btn">ADICIONAR AO CARRINHO</button>
            </form>
            
        </div>

        
        <div class="reviews">
            <h3>Reviews</h3>

            {{-- Exibição de Avaliações --}}
            @if($product->reviews->isEmpty())
                <p>Este produto ainda não possui avaliações. Seja o primeiro a avaliar!</p>
                <p></p>
            @else
                <div class="average-rating">
                    <strong>Média:</strong> {{ number_format($product->reviews->avg('rating'), 1) }}/5 ★
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
                            <button type="submit" class="delete-btn" style="display: none;">Excluir</button>
                        </form>
                        <button class="edit-btn" onclick="showEditForm({{ $review->id }})" style="display: none;">Editar</button>
                    @endif

                    {{-- Formulário de edição --}}
                    @if ($review->user_id === auth()->id())
                        <form action="{{ route('reviews.update', $review->id) }}" method="POST" class="edit-form" style="display: none;">
                            @csrf
                            @method('PUT')
                            <div>
                                <label for="rating-{{ $review->id }}">Avaliação:</label>
                                <input type="number" id="rating-{{ $review->id }}" name="rating" value="{{ $review->rating }}" min="0" max="5" required>
                            </div>
                            <div>
                                <label for="description-{{ $review->id }}">Descrição:</label>
                                <textarea id="description-{{ $review->id }}" name="description" required>{{ $review->description }}</textarea>
                            </div>
                            <button type="submit" class="save-btn">Salvar</button>
                            <button type="button" class="cancel-btn" onclick="hideEditForm({{ $review->id }})">Cancelar</button>
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
                        <h4>Adicionar sua avaliação:</h4>
                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div>
                                <label for="rating">Avaliação:</label>
                                <input type="number" id="rating" name="rating" min="0" max="5" required>
                            </div>
                            <div>
                                <label for="description">Descrição:</label>
                                <textarea id="description" name="description" required></textarea>
                            </div>
                            <button type="submit" class="submit-btn">Enviar Avaliação</button>
                        </form>
                    </div>
                @else
                    <p>Você já avaliou este produto. Edite ou exclua a avaliação existente.</p>
                @endif
            @else
                <p>Você precisa comprar este produto antes de avaliá-lo.</p>
            @endif


            @endauth

            {{-- Mensagem para usuários não autenticados --}}
            @guest
                <p>Faça login para adicionar sua avaliação.</p>
            @endguest
        </div>
    </div>

    @endsection



</body>

