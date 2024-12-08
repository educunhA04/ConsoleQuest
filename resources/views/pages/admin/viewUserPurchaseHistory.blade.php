@extends('layouts.admin')

@section('content')
<div class="user-purchase-history">
    <h1>{{ $user->name }}'s Purchase History</h1>
    @if($user->orders->isEmpty())
        <p>No purchases found for this user.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Products</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
            @foreach($user->orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A' }}</td>
                    <td>
                        <ul>
                            @foreach($order->orderProducts as $orderProduct)
                                <li>
                                    {{ $orderProduct->product->name }} 
                                    (Quantity: {{ $orderProduct->quantity }}) - 
                                    €{{ number_format($orderProduct->product->price * $orderProduct->quantity, 2) }}
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        €{{ number_format($order->orderProducts->sum(fn($op) => $op->product->price * $op->quantity), 2) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
