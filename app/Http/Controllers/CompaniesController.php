<?php

namespace CVS\Http\Controllers;

use CVS\Company;
use Exception;
use Illuminate\Http\Request;

use CVS\Http\Requests;

class CompaniesController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['index']]);
    }

    /**
     * Returns all Companies
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index(Request $request)
    {
        if ($request->has('short')) {
            return Company::all();
        }

        return Company::with('recruiters')->get();
    }

    /**
     * @param Company $company
     * Returns the specified Company
     * @return Company
     */
    public function show(Company $company)
    {
        $this->authorize('show-company', $company);

        return $company;
    }

    /**
     * @param Company $company
     * Returns the Recruiters of the specified Company
     * @return \Illuminate\Http\JsonResponse
     */
    public function showRecruiters(Company $company)
    {
        $this->authorize('show-company', $company);

        $companyRecruiters = $company::with('recruiters.user')
            ->where('id', $company->id)
            ->first();

        return response()->json($companyRecruiters->recruiters);
    }

    /**
     * @param Request $request
     * @param Company $company
     * Updates a Company
     * @return Company
     */
    public function update(Request $request, Company $company)
    {
        $this->authorize('update-company', $company);

        try {
            $company->update($request->only(['name']));
            return $company;
        } catch (Exception $e) {
            abort(500);
        }
    }
}
