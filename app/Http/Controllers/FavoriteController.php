<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Enregistre un stage en tant que favori.
     *
     * @param Request $request
     * @param $id ID du stage
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveIntern(Request $request, $id)
    {
        $intern = Intern::find($id);
        $intern->favorites()->attach(auth()->user()->id);

        return response()->json(['message' => 'Stage ajouté aux favoris'], 200);
    }

    /**
     * Supprime un stage des favoris.
     *
     * @param Request $request
     * @param $id ID du stage
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsaveIntern(Request $request, $id)
    {
        $intern = Intern::find($id);
        $intern->favorites()->detach(auth()->user()->id);

        return response()->json(['message' => 'Stage supprimé des favoris'], 200);
    }
}