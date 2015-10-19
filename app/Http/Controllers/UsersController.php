<?php

namespace CVS\Http\Controllers;

use CVS\Candidate;
use CVS\Company;
use CVS\Recruiter;
use CVS\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth');
	}

	/**
	 * Returns all Users
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getUsers()
	{
		$this->authorize('show-all-users');

		return User::all();
	}

	/**
	 * @param User $user
	 * Return the specified User
	 * @return User
	 */
	public function getUser(User $user)
	{
		$this->authorize('show-user', $user);

		return $user;
	}

	/**
	 * @param User $user
	 * Delete the specified User
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function deleteUser(User $user)
	{
		$this->authorize('delete-user', $user);

		return $user->delete() ? response()->json('User deleted.', 200) : response()->json('User NOT deleted.', 500);
	}

	/**
	 * Returns all Users' emails
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getEmails()
	{
		$this->authorize('get-users-emails');

		$emails = [];

		// Getting recruiters
		$recruiters = Recruiter::with(['user', 'company'])->get();
		foreach($recruiters as $recruiter)
			$emails[] = [
				'user_ido' => $recruiter->user->ido,
				'email' => $recruiter->user->email,
				'identity' => $recruiter->user->firstname . ' ' . $recruiter->user->lastname,
				'profile' => $recruiter->company->name
			];

		// Getting candidates
		$candidates = Candidate::with('user')->get();
		foreach($candidates as $candidate)
			$emails[] = [
				'user_ido' => $candidate->user->ido,
				'email' => $candidate->user->email,
				'identity' => $candidate->user->firstname . ' ' . $candidate->user->lastname,
				'profile' => 'Étudiant ' . trim($candidate->grade . ' ' . $candidate->education)
			];

		return response()->json($emails);
	}

	/**
	 * Returns all Users' phone numbers
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getPhoneNumbers()
	{
		$this->authorize('get-users-phonenumbers');

		$phones = [];

		// Getting recruiters
		$recruiters = Recruiter::with(['user', 'company'])->get();
		foreach($recruiters as $recruiter)
			if (!empty($recruiter->user->phone))
				$phones[] = [
					'user_ido' => $recruiter->user->ido,
					'phone' => $recruiter->user->phone,
					'phone_formatted' => $recruiter->user->phone_formatted,
					'identity' => $recruiter->user->firstname . ' ' . $recruiter->user->lastname,
					'profile' => $recruiter->company->name
				];

		// Getting candidates
		$candidates = Candidate::with('user')->get();
		foreach($candidates as $candidate)
			if (!empty($candidate->user->phone))
				$phones[] = [
					'user_ido' => $candidate->user->ido,
					'phone' => $candidate->user->phone,
					'phone_formatted' => $candidate->user->phone_formatted,
					'identity' => $candidate->user->firstname . ' ' . $candidate->user->lastname,
					'profile' => 'Étudiant ' . trim($candidate->grade . ' ' . $candidate->education)
				];

		return response()->json($phones);
	}

	public function getGroups()
	{
		$this->authorize('get-users-groups');

		$groups = [];

		// All users
		$groups[] = ['name' => 'Tous les utilisateurs', 'users' => User::getAllIdos()];

		// All recruiters
		$groups[] = ['name' => 'Tous les recruteurs', 'users' => Recruiter::getAllIdos()];

		// All candidates
		$groups[] = ['name' => 'Tous les étudiants', 'users' => Candidate::getAllIdos()];

		// Candidates by grades
		foreach(Candidate::getIdosGroupedByGrades() as $grade => $usersIdos)
			$groups[] = ['name' => "Étudiants $grade", 'users' => $usersIdos];

		// Candidates by education
		foreach(Candidate::getIdosGroupedByEducations() as $education => $usersIdos)
			$groups[] = ['name' => "Étudiants $education", 'users' => $usersIdos];

		// Candidates by grades AND education
		foreach(Candidate::getIdosGroupedByGradesAndEducations() as $gradeAndEducation => $usersIdos)
			$groups[] = ['name' => "Étudiants $gradeAndEducation", 'users' => $usersIdos];

		// Specific companies
		$companies = Company::with('recruiters.user')->get();
		foreach(Company::getIdosGroupedByCompanies() as $company => $usersIdos)
			$groups[] = ['name' => $company, 'users' => $usersIdos];

		return response()->json($groups);
	}
}