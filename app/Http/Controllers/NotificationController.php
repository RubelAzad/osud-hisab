<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Notification::latest()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Notification $notification): RedirectResponse
    {
        $notification->update(['is_read' => true]);

        return back();
    }

    public function markAllRead(): RedirectResponse
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
