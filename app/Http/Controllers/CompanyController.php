<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company =  Company::all();
        return response()->json($company);;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company->create($request->validated());
        return response()->json($company, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validatedData = $request->validated();

        $company = Company::update($validatedData);

        return response()->json($company, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->destroy();

        return response()->json(null,204);
    }
}