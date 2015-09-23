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

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (Auth::user()->organizer) {
            return Company::with('recruiters')->get();
        }

        abort(401);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
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
        if (Auth::user()->organizer) {
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
