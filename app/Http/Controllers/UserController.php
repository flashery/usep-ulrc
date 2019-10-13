<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();
        return view('users', compact('users'));
    }

    public function store() 
    {
        
    }

    public function profile()
    {
        $user = auth()->user();

        return view('profile', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $success = $user->update($request->all());

        return response()->json($success);
    }
}
