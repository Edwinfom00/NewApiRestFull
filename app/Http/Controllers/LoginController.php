<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Generate API token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'message' => 'connecté avec succès',
            ], 200);
        }

        return response()->json(['error' => 'les informations d\'identification invalides'], 401);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->tokens()->delete(); // Supprimer tous les jetons d'accès de l'utilisateur
        }

        return response()->json(['message' => 'L\'utilisateur s\'est déconnecté avec succès'], 200);
    }

    public function loginWithGoogle(Request $request)
    {
        try {
            $user = Socialite::driver('google')->user();

            // Vérifiez si l'utilisateur existe dans votre base de données

            $existingUser = User::where('email', $user->email)->first();

            if ($existingUser) {
                // Générez un token d'API pour l'utilisateur
                $token = $existingUser->createToken('api-token');

                return response()->json([
                    'success' => true,
                    'token' => $token->plainTextToken,
                    'user' => $existingUser,
                ]);
            } else {
                // Créez un nouvel utilisateur
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => bcrypt('password'),
                ]);

                // Générez un token d'API pour l'utilisateur
                $token = $newUser->createToken('api-token');

                return response()->json([
                    'success' => true,
                    'token' => $token->plainTextToken,
                    'user' => $newUser,
                ]);
            }

        } catch (Exception $e) {
            // Gérer l'erreur
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la connexion avec Google.',
            ], 400);
        }
    }
}
