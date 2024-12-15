<div class="notifications">
    <ul>
        @foreach($notifications as $notification)
        <li class="notification-item {{ $notification->viewed ? 'viewed' : '' }}" 
            data-id="{{ $notification->id }}">
            <strong>{{ $notification->description }}</strong>
            <p>{{ $notification->date }}</p>
        </li>
        @endforeach
    </ul>
</div>
