<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(){
         $user = Auth::user();
         // Mark all unread notifications as read
         $user->unreadNotifications->markAsRead();
         $notifications = $user->notifications()->paginate(20);
         
          return view('notifications', compact('notifications'));
    }
}
