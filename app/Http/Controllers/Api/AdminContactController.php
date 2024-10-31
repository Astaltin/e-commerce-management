<?php

namespace App\Http\Controllers\Api;

use App\Models\Contact;
use App\Http\Controllers\Controller;

class AdminContactController extends Controller
{
    public function index()
    {
        return response()-> json([
            'contacts' => Contact::all(),
        ]);
    }
}
