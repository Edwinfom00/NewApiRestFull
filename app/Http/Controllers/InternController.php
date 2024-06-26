<?php

namespace App\Http\Controllers;

use App\Http\Requests\InternFormRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\Intern;
use App\Models\Post;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InternController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['employer', 'verified'], ['except' => ['index', 'show', 'apply', 'allInterns', 'category', 'searchInterns']]);
    // }

    /**
     * Afficher une liste des ressources.
     */
    public function index()
    {
        $interns = Intern::orderBy('created_at', 'desc')->paginate(25);
        // $companies = Company::inRandomOrder()->take(12)->get();
        // $posts = Post::where('status', 1)->get();
        // $testimonial = Testimonial::where('status', 1)->first();

        return response()->json([
            $interns,
            // 'companies' => $companies,
            // 'posts' => $posts,
            // 'testimonial' => $testimonial,
            // 'categories' => $categories,
        ], 200);
    }

    /**
     * Stocker une nouvelle ressource créée dans la base de donnee
     */
    public function store(InternFormRequest $request)
    {
        $user_id = auth()->user()->id;
        $company = Company::where('user_id', $user_id)->first();
        $company_id = $company->id;

        $intern = Intern::create([
            'user_id' => $user_id,
            'company_id' => $company_id,
            'title' => request('title'),
            'slug' => Str::slug(request('title')),
            'description' => request('description'),
            'roles' => request('roles'), // to define the competence of the user where postuled
            'category_id' => request('category'),
            'company_name' => request('company_name'),
            'company_website' => request('company_website'),
            'company_location' => request('company_location'),
            'position' => request('position'),
            'address' => request('address'),
            'type' => request('type'),
            'status' => request('status'),
            'last_date' => request('last_date'),
            'start_date' => request('start_date'),
            'end_date' => request('end_date'),
        ]);

        return response()->json(['message' => 'Internship posted Successfully.', $intern], 201);
    }

    /**
     * Afficher tous les stages.
     */
    public function allInterns(Request $request)
    {
        $title = $request->get('title');
        $type = $request->get('type');
        $category = $request->get('category_id');
        $address = $request->get('address');

        if ($title || $type || $category || $address) {
            $interns = Intern::where('title', 'LIKE', '%'.$title.'%')
                ->orWhere('type', $type)
                ->orWhere('category_id', $category)
                ->orWhere('address', $address)
                ->paginate(25);

            return response()->json(compact('interns'), 200);
        } else {

            $interns = Intern::latest()->paginate(25);

            return response()->json(compact('interns'), 200);
        }
    }

    /**
     * Afficher la ressource spécifique.
     */
    public function show($id)
    {
        $intern = Intern::findOrFail($id);

        $internRecommendation = $this->internRecommendation($intern);

        return response()->json([$intern], 200);
    }

    public function internRecommendation($intern)
    {
        $data = [];

        // Récupérer les stages basés sur la catégorie du stage donné
        $internsBasedOnCategory = Intern::latest()
            ->where('category_id', $intern->category_id)
            ->whereDate('last_date', '>', date('Y-m-d'))
            ->where('id', '!=', $intern->id)
            ->where('status', 1)
            ->limit(5)
            ->get();

        array_push($data, $internsBasedOnCategory);

        // Récupérer les stages basés sur la société du stage donné
        $internsBasedOnCompany = Intern::latest()
            ->where('company_id', $intern->company_id)
            ->whereDate('last_date', '>', date('Y-m-d'))
            ->where('id', '!=', $intern->id)
            ->where('status', 1)
            ->limit(5)
            ->get();
        array_push($data, $internsBasedOnCompany);

        // Récupérer les stages basés sur le poste du stage donné
        $internsBasedOnPosition = Intern::latest()
            ->where('position', 'LIKE', '%'.$intern->position.'%')
            ->where('status', 1)
            ->limit(5)
            ->get();

        array_push($data, $internsBasedOnPosition);

        // Créer une collection à partir des données
        $collection = collect($data);

        // Supprimer les doublons basés sur l'identifiant du stage
        $unique = $collection->unique('id');

        // Récupérer le premier stage unique de la collection
        $internRecommendation = $unique->values()->first();

        return $internRecommendation;
    }

    /**
     * Récupérer les stages de l'entreprise.
     */
    public function myintern()
    {
        $interns = Intern::where('user_id', auth()->user()->id)->get();

        return response()->json(compact('interns'), 200);
    }

    public function edit($id)
    {
        $intern = Intern::findOrFail($id);

        return response()->json(compact('intern'), 200);
    }

    /**
     * Mettre à jour la ressource spécifiée dans le stockage.
     */
    public function update(Request $request, $id)
    {
        $intern = Intern::findOrFail($id);
        $intern->update($request->all());

        return response()->json(['message' => 'Internship updated Successfully.', 'data' => $intern], 200);
    }

    /**
     * Méthode d'application de stage.
     */
    public function apply(Request $request, $id)
    {
        $intern = Intern::findOrFail($id);

        // Vérifiez si l'utilisateur a déjà postulé.
        if ($intern->users()->where('users.id', Auth::user()->id)->exists()) {
            return response()->json(['message' => 'You have already applied to this internship.'], 400);
        }

        // Vérifiez si le nombre maximum de places a été atteint.
        $maxPlaces = $intern->max_places; // Assurez-vous que $intern a un attribut max_places.
        $currentApplications = $intern->users()->count();

        if ($currentApplications >= $maxPlaces) {
            return response()->json(['message' => 'No places left for this internship.'], 400);
        }

        // Attacher l'utilisateur seulement si les vérifications ci-dessus échouent.
        $intern->users()->attach(Auth::user()->id);

        return response()->json(['message' => 'Internship applied Successfully.'], 200);
    }
    // public function apply(Request $request, $id)
    // {
    //     try {
    //         $intern = Intern::findOrFail($id);

    //         // Vérifiez si l'utilisateur a déjà postulé.
    //         if ($intern->users()->where('id', Auth::user()->id)->exists()) {
    //             throw new Exception('Vous avez déjà postulé à ce stage.');
    //         }

    //         // Vérifiez si le nombre maximum de places a été atteint.
    //         $maxPlaces = $intern->max_places; // Assurez-vous que $intern a un attribut max_places.
    //         $currentApplications = $intern->users()->count();

    //         if ($currentApplications >= $maxPlaces) {
    //             throw new Exception('Plus de places disponibles pour ce stage.');
    //         }

    //         // Attacher l'utilisateur
    //         $intern->users()->attach(Auth::user()->id);

    //         return response()->json(['message' => 'Candidature au stage réussie.'], 200);
    //     } catch (Exception $e) {
    //         return response()->json(['message' => $e->getMessage()], 400);
    //     }
    // }

    // Méthode de récupération des candidats pour un stage
    public function applicant()
    {
        $applicants = Intern::has('users')->where('user_id', auth()->user()->id)->get();

        return response()->json(compact('applicants'), 200);
    }

    public function searchInterns(Request $request)
    {
        // $keyword = $request->get('keyword');
        // $interns = Intern::where('title', 'like', '%'.$keyword.'%')
        //     ->orWhere('position', 'like', '%'.$keyword.'%')
        //     ->orWhere('address', 'like', '%'.$keyword.'%')
        //     ->get();

        // return response()->json(compact('interns'), 200);
        $query = Intern::query();
        $data = $request->input('search_interns');

        if ($data) {
            $escapedData = addslashes($data); // Échapper les apostrophes simples dans la chaîne de recherche
            $query->whereRaw("title LIKE '%".$escapedData."%'")
                ->orWhereRaw("position LIKE '%".$escapedData."%'")
                ->orWhereRaw("address LIKE '%".$escapedData."%'");
        }

        return $query->get();
    }

    // Activer/désactiver un stage
    public function internToggle($id)
    {
        $intern = Intern::find($id);
        $intern->status = ! $intern->status;
        $intern->save();

        return response()->json(['message' => 'Internship Status Updated Successfully!'], 200);
    }

    // Supprimer un stage
    public function deleteIntern($id)
    {
        $intern = Intern::find($id);
        $intern->delete();

        return response()->json(['message' => 'Internship Deleted Successfully!'], 200);
    }
}
