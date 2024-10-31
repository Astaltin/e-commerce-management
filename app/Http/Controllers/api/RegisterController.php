<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function create(): JsonResponse
    {
        return response()->json(['message' => 'Display registration form'], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $attributes = $request->validate([
            'username' => ['required', 'max:255', Rule::unique('users', 'username')],
            'password' => ['required', 'min:6', 'max:255'],
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'landmark' => ['max:255'],
            'street' => ['required', 'max:255'],
            'barangay' => ['required', 'max:255'],
            'city' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'mobile_number' => ['required', 'max:255'],
        ]);

        $user = User::create($attributes);
        
        if (auth()->attempt(['username' => $attributes['username'], 'password' => $attributes['password']])) {
            return response()->json(['message' => 'Your account has been created', 'user' => $user], 201);
        }

        return response()->json(['message' => 'Failed to authenticate'], 401);
    }
}
