<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
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

    /**
     * Met à jour la lettre de motivation du profil utilisateur.
     */
    public function updateCoverLetter(Request $request)
    {
        $user_id = auth()->user()->id;

        // Validation des données envoyées dans la requête
        $request->validate([
            'cover_letter' => 'required|mimes:pdf|max:1024',
        ]);

        try {
            // Supprime l'ancienne lettre de motivation le cas échéant
            $oldCoverLetter = Profile::where('user_id', $user_id)->value('cover_letter');
            if ($oldCoverLetter) {
                Storage::delete($oldCoverLetter);
            }

            // Stocke la nouvelle lettre de motivation dans le système de fichiers
            $coverLetter = $request->file('cover_letter')->store('public/files');
            // Met à jour le chemin de la lettre de motivation dans la base de données
            Profile::where('user_id', $user_id)->update([
                'cover_letter' => $coverLetter,
            ]);

            // Retourne une réponse JSON avec un message de succès
            return response()->json(['message' => 'Lettre de motivation mise à jour avec succès.'], 200);
        } catch (\Exception $e) {
            // Retourne une réponse JSON avec un message d'erreur en cas d'échec
            return response()->json(['error' => 'Une erreur s\'est produite lors du téléchargement du fichier.'], 500);
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
