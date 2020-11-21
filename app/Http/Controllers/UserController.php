<?php

namespace App\Http\Controllers;

use App\SentList;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();
        
        return view('user.index', compact('users'));
    }

    public function lists($id)
    {
        $user = User::findOrFail($id);
        $lists = $user->sent_lists;
        
        return view('user.lists', compact('lists', 'user'));
    }
}
