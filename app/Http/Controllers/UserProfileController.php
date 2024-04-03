<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    /**
     * Affiche les détails du profil utilisateur.
     */
    public function show($user_id)
    {
        // Récupère le profil de l'utilisateur en fonction de l'identifiant utilisateur
        $profile = Profile::where('user_id', $user_id)->first();

        return response()->json(['profile' => $profile], 200);
    }

    /**
     * Met à jour les informations du profil utilisateur.
     */
    public function update(Request $request)
    {
        $user_id = auth()->user()->id;

        // Validation des données envoyées dans la requête
        $request->validate([
            'address' => 'required|min:10|max:255',
            'phone' => 'required|digits:9',
            'bio' => 'required|min:30|max:1000',
            'gender' => 'required',
        ]);

        // Met à jour les informations du profil dans la base de données
        Profile::where('user_id', $user_id)->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'dob' => $request->dob,
            'gender' => $request->gender,
        ]);

        // Retourne une réponse JSON avec un message de succès
        return response()->json(['message' => 'Informations du profil mises à jour avec succès.'], 200);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        // Validation des champs
        $request->validate([
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8',
        ]);

        // Vérification de l'ancien mot de passe
        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Le mot de passe actuel est incorrect.',
            ], 422);
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        Profile::where('user_id', $user->id)->update([
            'password' => Hash::make($request->new_password),

        ]);

        // Envoi d'une réponse de succès
        return response()->json([
            'message' => 'Le mot de passe a été mis à jour avec succès.',
        ]);
    }

    /**
     * Met à jour la lettre de motivation du profil utilisateur.
     */
    public function updateCoverLetter(Request $request)
    {
        $profile = new Profile;
        $user_id = auth()->user()->id;

        if ($request->hasFile('cover_letter')) {
            $completeFileName = $request->file('cover_letter')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $request->file('cover_letter')->getClientOriginalExtension();
            $compic = str_replace('', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$extension;
            $path = $request->file('cover_letter')->storeAs('public/cover_letter', $compic);

            $profile->cover_letter = $profile::where('user_id', $user_id)->update(['cover_letter' => $compic]);

            return response()->json(['cover_letter' => $compic, 'user_id' => $user_id, 'message' => 'mis a jour avec success']);
        }
    }

    // Méthodes similaires pour la mise à jour du CV, de l'avatar et d'autres fonctionnalités...

    public function updateResume(Request $request)
    {
        $user_id = auth()->user()->id;

        $request->validate([
            'resume' => 'required|mimes:pdf|max:2048',
        ]);

        try {
            // Supprime l'ancienne Cv le cas échéant
            $oldResume = Profile::where('user_id', $user_id)->value('resume');
            if ($oldResume) {
                Storage::delete($oldResume);
            }

            // Stocke la nouveau CV dans le système de fichiers
            $resume = $request->file('resume')->store('public/files');
            Profile::where('user_id', $user_id)->update(['resume' => $resume]);

            // Retourne une réponse JSON avec un message de succès
            return response()->json(['message' => 'CV mis à jour avec succès.'], 200);

        } catch (\Exception $e) {
            // Retourne une réponse JSON avec un message d'erreur en cas d'échec
            return response()->json(['error' => 'Une erreur s\'est produite lors du téléchargement du fichier.'], 500);
        }
    }

    public function updateAvatar(Request $request)
    {
        $user_id = auth()->user()->id;

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $oldAvatar = Profile::where('user_id', $user_id)->value('avatar');

            // Déterminer si l'image provient d'un fichier ou d'une URL
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
            } else {
                // Gérer l'image provenant d'une URL (non abordé ici)
            }

            if ($oldAvatar && Storage::exists('public/avatars/'.$oldAvatar)) {
                Storage::delete('public/avatars/'.$oldAvatar);
            }

            // Stocker l'image
            $newAvatarPath = $avatar->store('public/avatars');

            // Mettre à jour la base de données
            Profile::where('user_id', $user_id)->update(['avatar' => $newAvatarPath]);

            return response()->json(['message' => 'Avatar mis à jour avec succès.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur s\'est produite lors du téléchargement du fichier.'], 500);
        }
    }
}
