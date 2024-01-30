<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

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
            'address' => 'required|min:20|max:255',
            'phone'=> 'required|digits:11',
            'bio'=> 'required|min:30|max:450',
        ]);

        // Met à jour les informations du profil dans la base de données
        Profile::where('user_id', $user_id)->update([
            'address'=> $request->address,
            'phone'=> $request->phone,
            'experience'=> $request->experience,
            'bio'=> $request->bio,
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
            'cover_letter'=>'required|mimes:pdf|max:1024',
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
                'cover_letter' => $coverLetter
            ]);

            // Retourne une réponse JSON avec un message de succès
            return response()->json(['message' => 'Lettre de motivation mise à jour avec succès.'], 200);
        } catch (\Exception $e) {
            // Retourne une réponse JSON avec un message d'erreur en cas d'échec
            return response()->json(['error' => 'Une erreur s\'est produite lors du téléchargement du fichier.'], 500);
        }
    }

    // Méthodes similaires pour la mise à jour du CV, de l'avatar et d'autres fonctionnalités...
}