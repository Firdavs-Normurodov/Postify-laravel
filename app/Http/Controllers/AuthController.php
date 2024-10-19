<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        $users = User::all(); // Barcha foydalanuvchilarni olish
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::find($id); // Foydalanuvchini ID orqali qidirish

        if (!$user) {
            return response()->json(['error' => 'Foydalanuvchi topilmadi'], 404);
        }

        return response()->json($user);
    }

    public function getProfile(Request $request)
    {
        $user = Auth::user(); // Hozirgi foydalanuvchini olish

        return response()->json([
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email,
            'image_url' => $user->image_url,
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'image_url' => 'nullable|url',
        ]);

        // Agar image_url bo'sh bo'lsa, standart URLni belgilang
        $data['image_url'] = $data['image_url'] ?? 'https://i.sstatic.net/l60Hf.png';

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json($user, 201);
    }

    public function login(Request $request)
    {
        // Email va parolni majburiy qilib validatsiya qilish
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Auth orqali tekshirish
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            // User ma'lumotlarini qaytarish
            return response()->json([
                'access_token' => $token,
                'user' => [
                    'name' => $user->name,
                    'surname' => $user->surname,  // Userning familiyasini olish
                    'email' => $user->email,
                    'image_url' => $user->image_url, // Userning rasm URL
                ]
            ]);
        }

        // Agar foydalanuvchi login qilolmasa, Unauthorized xatosini qaytarish
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        // Foydalanuvchining barcha tokenlarini o'chirish
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Foydalanuvchi tizimdan chiqdi']);
    }
}
