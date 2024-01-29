<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\InternFormRequest;
use App\Models\Intern;
use Illuminate\Http\Request;

class InternController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $intern = Intern::all();
        return response()->json($intern);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InternFormRequest $request)
    {
        $validatedData = $request->validated();

        $intern = Intern::create($validatedData);

        return response()->json($intern, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Intern $intern)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InternFormRequest $request, Intern $intern)
    {
        $validatedData = $request->validated();

        $intern = Intern::update($validatedData);

        return response()->json($intern, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Intern $intern)
    {
        $intern->delete();

        return response()->json(null, 204);
    }
}