<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function notifications()
    {
        
        auth()->user()->unreadNotifications->markAsRead();
        // dd(auth()->user()->notifications->first());
        return view('users.notifications')->withNotifications(auth()->user()->notifications()->paginate(5));
    }
}
