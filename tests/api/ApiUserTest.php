<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiUserTest extends TestCase
{
	use DatabaseTransactions;

	public $organizer;
	public $candidate;
	public $recruiter;

	/**
	 * Getting all Users from an Organizer account (should send 200)
	 */
	public function testGetUsersFromOrganizer()
	{
		$user = factory(\CVS\User::class)->create([
			'organizer' => true
		]);

		$token = JWTAuth::fromUser($user);

		$this->get('/users', ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200)
			->seeJson();
	}

	/**
	 * Getting all Users from an account (should send 401)
	 */
	public function testGetUsersFromConnectedAccount()
	{
		$user = factory(\CVS\User::class)->create([
			'organizer' => false
		]);

		$token = JWTAuth::fromUser($user);

		$this->get('/users', ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(401);
	}

	/**
	 * Getting all Users from anonymous (should sent 401)
	 */
	public function testGetUsersFromAnonymous()
	{
		$this->get('/users')
			->seeStatusCode(400);
	}

	/**
	 * Getting a specified User as Organizer (should send the User)
	 */
	public function testGetUserFromOrganizer()
	{
		$organizer = factory(\CVS\User::class)->create([
			'organizer' => true
		]);

		$token = JWTAuth::fromUser($organizer);

		$userId = rand(1, 10);

		$this->get("/users/$userId", ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200)
			->seeJsonContains(['id' => $userId]);
	}

	/**
	 * Getting a specified User as Organizer (should then the User)
	 */
	public function testGetUserFromHimself()
	{
		$user = \CVS\User::find(rand(1, 10));

		$token = JWTAuth::fromUser($user);

		$this->get('/users/' . $user->id,  ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200)
			->seeJsonContains(['id' => $user->id]);
	}

	/**
	 * Getting a User from another one (should send 401)
	 */
	public function testGetUserFromAnotherOne()
	{
		$userWhoLooks = \CVS\User::where('organizer', false)->first();
		$userLooked = \CVS\User::where('organizer', false)->where('id', '!=', $userWhoLooks->id)->first();

		$token = JWTAuth::fromUser($userWhoLooks);

		$this->get('/users/' . $userLooked->id, ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(401);
	}

	/**
	 * Getting a User from anonymous (should send 401)
	 */
	public function testGetUserFromAno()
	{
		$user = \CVS\User::find(rand(1, 10));

		$this->get('/users/' . $user->id)
			->seeStatusCode(400);
	}

	/**
	 * Deleting a User from Organizer (should send 200)
	 */
	public function testDeleteUserFromOrganizer()
	{
		$organizer = factory(\CVS\User::class)->create([
			'organizer' => true
		]);

		$token = JWTAuth::fromUser($organizer);

		$user = \CVS\User::where('organizer', false)->first();

		$this->delete('/users/' . $user->id, [], ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200);
	}

	public function testDeleteOrganizerFromOrganizer()
	{
		$organizer = factory(\CVS\User::class)->create([
			'organizer' => true
		]);

		$token = JWTAuth::fromUser($organizer);

		$user = \CVS\User::where('organizer', true)->first();

		$this->delete('/users/' . $user->id, [], ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(401);
	}

	/**
	 * Deleting an User from another one (should sent 400)
	 */
	public function testDeleteUserFromUser()
	{
		$user = \CVS\User::orderByRaw("RAND()")->first();

		$this->delete('/users/' . $user->id)
			->seeStatusCode(400);
	}
}
