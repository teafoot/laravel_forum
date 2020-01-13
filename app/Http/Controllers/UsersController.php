<?php

namespace LaravelForum\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function notifications() {
        auth()->user()->unreadNotifications->markAsRead();
        
        return view("users.notifications", [
            "notifications" => auth()->user()->notifications()->paginate(2)
        ]);
    }
}
