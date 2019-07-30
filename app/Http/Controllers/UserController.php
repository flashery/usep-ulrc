<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
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
