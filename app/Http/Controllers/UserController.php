<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 26/07/2015
 * Time: 17:35
 */

namespace CVS\Http\Controllers;


class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('organizer', ['only' => ['getIndex']]);
	}

	public function getIndex()
	{
		return response()->json('successfull response from controller');
	}
}