<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
    /**
     * Vérifie l'adresse email d'un utilisateur.
     *
     * @param EmailVerificationRequest $request La requête de vérification d'email.
     *
     * @return \Illuminate\Http\JsonResponse La réponse JSON indiquant la réussite ou l'échec de la vérification.
     */
    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email déjà vérifié'], 200);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json(['message' => 'Email vérifié avec succès'], 200);
    }

    /**
     * Envoie un nouveau lien de vérification d'email à l'utilisateur.
     *
     * @param Request $request La requête HTTP.
     *
     * @return \Illuminate\Http\JsonResponse La réponse JSON indiquant la réussite ou l'échec de l'envoi du lien.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email déjà vérifié'], 200);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Lien de vérification envoyé à votre adresse email'], 200);
    }
}