<div class="notifications">
    <h2>Your Notifications</h2>
    @if($notifications->isEmpty())
        <p>No notifications available.</p>
    @else
        <ul>
            @foreach($notifications as $notification)
                <li>
                    <strong>{{ $notification->description }}</strong>
                    <p>{{ $notification->date }}</p>
                    <p>Viewed: {{ $notification->viewed ? 'Yes' : 'No' }}</p>
                </li>
            @endforeach
        </ul>
    @endif
</div>
