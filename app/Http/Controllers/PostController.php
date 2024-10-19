<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Barcha postlarni olish
    public function index()
    {
        return Post::all();
    }

    // Yangi post yaratish
    // Yangi post yaratish
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'short_content' => 'required|string|max:500',
            'content' => 'required|string',
            'image_url' => 'nullable|string|url', // optional image URL
        ]);

        // Agar image_url bo'sh bo'lsa, standart URLni belgilang
        $post = new Post();
        $post->title = $validatedData['title'];
        $post->short_content = $validatedData['short_content'];
        $post->content = $validatedData['content'];
        $post->image_url = $validatedData['image_url'] ?? 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSKteJt4yIuQOY8dv1-oknm_gx4IRimwZ_rzOT0LY7NiFnd5JsBD8ofiiPhMGeaYpXEr-0&usqp=CAU';

        $post->user_id = Auth::id();
        $post->save();

        return response()->json([
            'message' => 'Post muvaffaqiyatli yaratildi!',
            'post' => $post,
        ], 201);
    }


    // Muayyan postni olish
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post);
    }

    // Postni yangilash
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'short_content' => 'sometimes|required|string|max:500',
            'content' => 'sometimes|required|string',
            'image_url' => 'nullable|string|url',
        ]);

        $post = Post::findOrFail($id);
        $post->update($validated);
        return response()->json($post);
    }

    // Postni o'chirish
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Postni yaratgan foydalanuvchini tekshirish
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Siz bu postni o\'chira olmaysiz.'], 403);
        }

        $post->delete();
        return response()->json(null, 204);
    }
}
