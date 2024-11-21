@extends('layouts.app')

@section('content')
    <h2>Search Results</h2>
    @if($products->isEmpty())
        <p>No products found matching your query.</p>
    @else
        <ul>
            @foreach($products as $product)
                <li>{{ $product->name }} - {{ $product->category->type }}</li>
            @endforeach
        </ul>
    @endif
@endsection
