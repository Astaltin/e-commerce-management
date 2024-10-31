<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Informational endpoint for contact form submission.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        // Provide an informational JSON response for API usage
        return response()->json([
            'message' => 'Submit a message by sending a POST request to /api/contact.',
        ], 200);
    }

    /**
     * Store a new contact message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation with customized rules
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create contact message in database
        $contact = Contact::create($validator->validated());

        return response()->json([
            'message' => 'Message submitted successfully.',
            'data' => $contact,
        ], 201);
    }
}
