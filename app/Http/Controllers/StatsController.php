<?php

namespace CVS\Http\Controllers;

use CVS\Interview;
use Illuminate\Http\Request;

use CVS\Http\Requests;
use CVS\Http\Controllers\Controller;

class StatsController extends Controller
{
    public function getInterviewsStats()
    {
		return response()->json([
			'total' => Interview::all()->count(),
			'available' => Interview::whereNull('candidate_id')->count(),
			'taken' => Interview::whereNotNull('candidate_id')->count(),
		]);
    }
}
