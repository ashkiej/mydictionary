<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = $request->user()->favorites()->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $favorites
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'word' => 'required|string|max:255',
            'phonetics' => 'nullable|array',
            'definitions' => 'required|array',
            'part_of_speech' => 'required|array',
            'example_usage' => 'nullable|array',
            'synonyms' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if word already exists in favorites
        $existingFavorite = $request->user()->favorites()
            ->where('word', $request->word)
            ->first();

        if ($existingFavorite) {
            return response()->json([
                'success' => false,
                'message' => 'Word already in favorites',
                'data' => $existingFavorite
            ], 409);
        }

        $favorite = $request->user()->favorites()->create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Word added to favorites',
            'data' => $favorite
        ], 201);
    }

    public function update(Request $request, Favorite $favorite)
    {
        // Check if favorite belongs to authenticated user
        if ($favorite->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $favorite->update($request->only('notes'));

        return response()->json([
            'success' => true,
            'message' => 'Notes updated successfully',
            'data' => $favorite
        ]);
    }

    public function destroy(Request $request, Favorite $favorite)
    {
        // Check if favorite belongs to authenticated user
        if ($favorite->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Word removed from favorites'
        ]);
    }
}
