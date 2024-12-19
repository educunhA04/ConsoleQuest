<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\NotificationUser;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Fetch user's notifications
        $notifications = NotificationUser::where('user_id', $userId)
            ->with('notification')
            ->orderBy('notification_id', 'desc')
            ->get()
            ->pluck('notification');

        // If the request is an AJAX request, return only the notifications partial
        if ($request->ajax()) {
            return view('partials.notifications', compact('notifications'))->render();
        }

        // Otherwise, return a full response
        return view('partials.notifications', compact('notifications'));
    }

    public function show($notificationId)
    {
        $userId = auth()->id();

        // Fetch the specific notification for the authenticated user through the pivot table
        $notification = NotificationUser::where('user_id', $userId)
            ->where('notification_id', $notificationId)
            ->with('notification') 
            ->orderBy('notification_id', 'desc')
            ->firstOrFail()
            ->notification;

        return response()->json([
            'notification' => $notification
        ]);
    }

    public function markAsViewed($notificationId)
    {
        try {
            // Find the notification by its ID
            $notification = Notification::findOrFail($notificationId);
    
            // Mark it as viewed
            $notification->viewed = true;
            $notification->save();
    
            return response()->json(['message' => 'Notification marked as viewed'], 200);
        } catch (\Exception $e) {
            // Log and return error if something goes wrong
            \Log::error("Error marking notification as viewed: " . $e->getMessage());
            return response()->json(['error' => 'Failed to update notification'], 500);
        }
    }
    

}

