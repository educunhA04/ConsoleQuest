@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Avaliações</h1>
    @foreach($reviews as $review)
        <div class="review">
            <h3>{{ $review->user->name }}</h3>
            <p>Nota: {{ $review->rating }} / 5</p>
            <p>{{ $review->description }}</p>
        </div>
    @endforeach
</div>
@endsection
