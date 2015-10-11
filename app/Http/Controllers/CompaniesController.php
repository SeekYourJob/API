<?php

namespace CVS\Http\Controllers;

use Auth;
use CVS\Company;
use Illuminate\Http\Request;

use CVS\Http\Requests;
use CVS\Http\Controllers\Controller;
use Mockery\CountValidator\Exception;

class CompaniesController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function index()
    {
        if (Auth::user()->organizer) {
            return Company::with('recruiters')->get();
        }

        abort(401);
    }

    public function show(Company $company)
    {
        if (Auth::user()->organizer){
            return $company;
        }

        abort(401);
    }

    public function showRecruiters(Company $company)
    {
        if (Auth::user()->organizer ||
            (isset(Auth::user()->profile->company_id) && Auth::user()->profile->company_id == $company->id)
        ) {
            $companyRecruiters = $company::with('recruiters.user')
                ->where('id', $company->id)
                ->first();

            return response()->json($companyRecruiters->recruiters);
        }

        abort(401);
    }

    public function update(Request $request, Company $company)
    {
        if (Auth::user()->organizer) {
            try {
                $company->update($request->only(['name']));
                return $company;
            } catch (Exception $e) {
                abort(500);
            }
        }

        abort(401);
    }
}
