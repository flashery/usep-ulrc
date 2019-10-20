<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

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

    public function store(Request $request)
    {
        return User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'can_edit' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
    }

    public function profile()
    {
        $user = auth()->user();

        return view('profile', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->all();

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->get('password'));
        }
        
        $success = $user->update($data);

        return response()->json($success);
    }
}
