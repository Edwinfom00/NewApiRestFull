<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
     // Cette méthode envoie un lien de réinitialisation de mot de passe à l'adresse e-mail spécifiée.
    public function sendResetLinkEmail(Request $request)
    {
         // Validation la demande en s'assurant que l'adresse e-mail est présente et au bon format.
        $request->validate(['email' => 'required|email']);

         // On utilise la classe Password pour envoyer un lien de réinitialisation de mot de passe à l'adresse e-mail spécifiée.

        $status = Password::sendResetLink(
            $request->only('email')
        );

        // On renvoie une réponse JSON en fonction du statut de l'envoi.


        return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => 'Reset link sent'], 200)
                : response()->json(['message' => __($status)], 400);
    }

    public function reset(Request $request)
    {
        // Validation de  la demande en s'assurant que le jeton, l'adresse e-mail et le nouveau mot de passe sont présents et au bon format.
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);


            //Utilisation de  la classe Password pour réinitialiser le mot de passe de l'utilisateur.

        $status = Password::reset(
            //hachage du nouveau mot de passe et on le met à jour dans la base de données.
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();


                // On déclenche l'événement PasswordReset.

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                ? response()->json(['message' => 'Password reset successfully'], 200)
                : response()->json(['message' => __($status)], 400);
    }
}