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


        $notifications = NotificationUser::where('user_id', $userId)
            ->with('notification')
            ->orderBy('notification_id', 'desc')
            ->get()
            ->pluck('notification')
            ->sortByDesc('id');

        // If the request is an AJAX request, return only the notifications partial
        if ($request->ajax()) {
            return view('partials.notifications', compact('notifications'))->render();
        }
        return view('partials.notifications', compact('notifications'));
    }

    public function show($notificationId)
    {
        $userId = auth()->id();

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
            
            $notification = Notification::findOrFail($notificationId);
    
            // Mark as viewed
            $notification->viewed = true;
            $notification->save();
    
            return response()->json(['message' => 'Notification marked as viewed'], 200);
        } catch (\Exception $e) {

            \Log::error("Error marking notification as viewed: " . $e->getMessage());
            return response()->json(['error' => 'Failed to update notification'], 500);
        }
    }
    

}

