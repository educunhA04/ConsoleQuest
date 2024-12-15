@extends('layouts.admin')

@section('content')
<div class="admin-dashboard">
    <h1>Welcome, {{ Auth::user()->name }}!</h1>

    @if (isset($users))
        <h2>Users:</h2>
        <div class="user-list">
            @foreach ($users as $user)
                <div class="user-card">
                    <h3>{{ $user->username }}</h3>
                    <p>Email: {{ $user->email }}</p>
                    <p>Name: {{ $user->name }}</p>
                    <form action="{{ route('admin.viewUser') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <button type="submit" class="button-link">View Profile</button>
                    </form>
                </div>
            @endforeach
        </div>
        <a href="{{ route('admin.createUser') }}" class="admin-button-link">New User</a>

    @elseif (isset($products))
        <h2>Products:</h2>
        <div class="admin-product-list">
            @foreach ($products as $product)
                <div class="admin-product-container-dashboard">
                    <a href="{{ route('admin.viewProduct', ['id' => $product->id]) }}" class="admin-product-link">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="admin-product-image">
                        <div class="admin-product-name">{{ $product->name }}</div>
                    </a>
                </div>
            @endforeach
        </div>
        <a href="{{ route('admin.createProduct') }}" class="admin-button-link">New Product</a>

<a href="#" class="admin-button-link" onclick="openTypeModal()">New Type</a>

<div class="typeModal">
    <div class="modal-content">
        <span class="close" onclick="closeTypeModal()">&times;</span>
        <h2>Add New Type</h2>
        <form id="typeForm" action="/admin/addType" method="POST">
            @csrf
            <label for="name">Type Name</label>
            <input type="text" id="name" name="name" required>
            <button type="submit" class="btn-submit">Add Type</button>
        </form>
    </div>
</div>



    @elseif (isset($reports))
        <h2>Reports:</h2>
        <div class="report-list">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Reason</th>
                        <th>Description</th>
                        <th>Review</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>{{ $report->id }}</td>
                            <td>{{ $report->user->name }} ({{ $report->user->email }})</td>
                            <td>{{ $report->reason }}</td>
                            <td>{{ $report->description }}</td>
                            <td>{{ $report->review->description }}</td>
                            <td class="actions-cell">
                                <form action="{{ route('admin.deleteReport', ['id' => $report->id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button-link-danger">Delete Report</button>
                                </form>
                                @if ($report->review)
                                    <form action="{{ route('admin.reviews.destroy', ['id' => $report->review->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button-link-danger">Delete Review</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
</div>
@endsection
