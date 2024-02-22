<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Intern;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Affiche la liste des entreprises.
     */
    public function index()
    {
        $companies = Company::latest()->paginate(12);

        return response()->json(['companies' => $companies], 200);
    }

    /**
     * Affiche les détails d'une entreprise.
     */
    public function show($id)
    {
        $company = Company::findOrFail($id);

        return response()->json(['company' => $company], 200);
    }

    /**
     * Creer une entreprise
     */
    public function store(Request $request)
    {
        $user_id = auth()->user()->id;

        $company = Company::create([
            'user_id' => $user_id,
            'cname' => request('cname'),
            'address' => request('address'),
            'phone' => request('phone'),
            'website' => request('website'),
            'slogan' => request('slogan'),
            'description' => request('description'),
        ]);

        return response()->json(['message' => 'Company Succefully Created', 'data' => $company], 201);

    }

    /**
     * Met à jour les informations de l'entreprise.
     */
    public function update(Request $request)
    {
        $user_id = auth()->user()->id;

        $request->validate([
            'cname' => 'required|min:5|max:250|string',
            'address' => 'required|min:20|max:450',
            'phone' => 'required|digits:11',
            'website' => 'required',
            'slogan' => 'required|min:10|max:100',
            'description' => 'required|min:100|max:4000',
        ]);

        Company::where('user_id', $user_id)->update([
            'address' => $request->address,
            'phone' => $request->phone,
            'website' => $request->website,
            'slogan' => $request->slogan,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Informations de l\'entreprise mises à jour avec succès.'], 200);
    }

    /**
     * Met à jour le logo de l'entreprise.
     */
    public function updateLogo(Request $request)
    {
        $user_id = auth()->user()->id;

        $request->validate([
            'logo' => 'required|mimes:jpeg,jpg,png|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            // Supprime l'ancien fichier logo
            $oldLogo = Company::where('user_id', $user_id)->value('logo');
            if (is_file(public_path('uploads/logo/'.$oldLogo))) {
                unlink(public_path('uploads/logo/'.$oldLogo));
            }

            $file->move('uploads/logo/', $filename);

            Company::where('user_id', $user_id)->update([
                'logo' => $filename,
            ]);

            return response()->json(['message' => 'Logo mis à jour avec succès.'], 200);
        }
    }

    /**
     * Met à jour la bannière de l'entreprise.
     */
    public function updateBanner(Request $request)
    {
        $user_id = auth()->user()->id;

        $request->validate([
            'banner' => 'required|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            // Supprime l'ancien fichier de bannière
            $oldBanner = Company::where('user_id', $user_id)->value('banner');
            if (is_file(public_path('uploads/banner/'.$oldBanner))) {
                unlink(public_path('uploads/banner/'.$oldBanner));
            }

            $file->move('uploads/banner/', $filename);

            Company::where('user_id', $user_id)->update([
                'banner' => $filename,
            ]);

            return response()->json(['message' => 'Bannière mise à jour avec succès.'], 200);
        }
    }

    /**
     * Affiche les offres de stage d'une entreprise.
     */
    public function interns($id)
    {
        $interns = Intern::where('user_id', $id)->get();

        return response()->json(['interns' => $interns], 200);
    }
}
