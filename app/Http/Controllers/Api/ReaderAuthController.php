<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ReaderAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:readers,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $reader = Reader::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'is_active' => true,
        ]);

        NewsletterSubscriber::firstOrCreate(
            ['email' => $reader->email],
            ['name' => $reader->name, 'is_active' => true]
        );

        $token = $reader->createToken('ebenin-mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'    => $reader->id,
                'name'  => $reader->name,
                'email' => $reader->email,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $reader = Reader::where('email', $data['email'])->first();

        if (!$reader || !Hash::check($data['password'], $reader->password)) {
            throw ValidationException::withMessages([
                'email' => ['Identifiants incorrects.'],
            ]);
        }

        $reader->tokens()->delete();
        $token = $reader->createToken('ebenin-mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'     => $reader->id,
                'name'   => $reader->name,
                'email'  => $reader->email,
                'avatar' => $reader->avatar ? asset($reader->avatar) : null,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnecté.']);
    }

    public function me(Request $request)
    {
        $user     = $request->user();
        $favorites = \App\Models\ReaderFavorite::where('user_type', 'reader')
            ->where('user_id', $user->id)->count();
        $comments  = \App\Models\comment::where('reader_mail', $user->email)->count();

        return response()->json([
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'avatar'     => $user->avatar ? asset($user->avatar) : null,
            'favorites'  => $favorites,
            'comments'   => $comments,
            'created_at' => $user->created_at->toDateString(),
        ]);
    }
}
