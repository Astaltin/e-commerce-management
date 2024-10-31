<?php

namespace App\Http\Controllers\api;

use App\Models\Flavor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminFlavorController extends Controller
{
    public function index()
    {
        return response()->json([
            'flavors' => Flavor::all(),
        ]);
    }

    public function create()
    {
        return response()->json([
            'message' => 'Create form endpoint for flavors',
        ]);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $flavor = Flavor::create($attributes);

        return response()->json([
            'message' => 'Flavor added successfully',
            'flavor' => $flavor,
        ]);
    }

    public function edit(Flavor $flavor)
    {
        return response()->json([
            'flavor' => $flavor,
            'message' => 'Edit form endpoint for flavors',
        ]);
    }

    public function update(Request $request, Flavor $flavor)
    {
        $attributes = $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $flavor->update($attributes);

        return response()->json([
            'message' => 'Flavor updated successfully',
            'flavor' => $flavor,
        ]);
    }

    public function destroy(Flavor $flavor)
    {
        $flavor->delete();

        return response()->json([
            'message' => 'Flavor deleted successfully',
        ]);
    }
}
