<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\NotificationUser;

class NotificationController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $notifications = NotificationUser::where('user_id', $userId)
            ->with('notification') // Load the related Notification model
            ->get()
            ->pluck('notification'); // Extract the notifications themselves

        return view('partials.notifications', compact('notifications'));

    }

    public function show($notificationId)
    {
        $userId = auth()->id();

        // Fetch the specific notification for the authenticated user through the pivot table
        $notification = NotificationUser::where('user_id', $userId)
            ->where('notification_id', $notificationId)
            ->with('notification') // Load the related Notification model
            ->firstOrFail()
            ->notification;

        return response()->json([
            'notification' => $notification
        ]);
    }
}

