<?php

namespace App\Http\Controllers\api;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $orders = Order::whereBelongsTo($user)->latest()->get();

        return response()->json([
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    public function destroy()
    {
        auth()->logout();

        return response()->json(['message' => 'You have successfully logged out']);
    }

    public function edit(Request $request)
    {
        $user = $request->user();

        return response()->json(['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $attributes = $request->validate([
            'street' => ['required', 'max:255'],
            'barangay' => ['required', 'max:255'],
            'city' => ['required', 'max:255'],
            'landmark' => ['required', 'max:255'],
            'mobile_number' => ['required', 'max:255'],
        ]);

        $user->update($attributes);

        return response()->json(['message' => 'Profile updated successfully']);
    }

    public function create()
    {
        return response()->json(['message' => 'Create session endpoint']);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'username' => ['required', 'max:255'],
            'password' => ['required', 'min:6', 'max:255'],
        ]);

        if (! auth()->attempt($attributes)) {
            throw ValidationException::withMessages([
                'username' => 'Your provided credentials could not be verified',
            ]);
        }
        
        session()->regenerate();
        $user = auth()->user();
        
        return response()->json([
            'message' => 'You have successfully logged in',
            'user' => $user,
            'is_admin' => $user->admin,
        ]);
    }
}
