<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
	use DatabaseTransactions;

	/**
	 * Getting all Users from an Organizer account (should send 200)
	 */
	public function testCanGetUsersFromOrganizer()
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
	public function testCannotGetUsersFromConnectedAccount()
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
	public function testCannotGetUsersFromAnonymous()
	{
		$this->get('/users')
			->seeStatusCode(400);
	}

	/**
	 * Getting a specified User as Organizer (should send the User)
	 */
	public function testCanGetUserFromOrganizer()
	{
		$organizer = factory(\CVS\User::class)->create([
			'organizer' => true
		]);

		$token = JWTAuth::fromUser($organizer);

		$userId = rand(1, 10);
		$userIdObfuscated = app('Optimus')->encode($userId);

		$this->get("/users/$userIdObfuscated", ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200)
			->seeJsonContains(['ido' => $userIdObfuscated]);
	}

	/**
	 * Getting a specified User as himself (should then the User)
	 */
	public function testCanGetUserFromHimself()
	{
		$user = \CVS\User::find(rand(1, 10));
		$userIdObfuscated = app('Optimus')->encode($user->id);

		$token = JWTAuth::fromUser($user);

		$this->get("/users/$userIdObfuscated",  ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200)
			->seeJsonContains(['ido' => $userIdObfuscated]);
	}

	/**
	 * Getting a User from another one (should send 401)
	 */
	public function testCannotGetUserFromAnotherOne()
	{
		$userWhoLooks = \CVS\User::where('organizer', false)->first();
		$userLooked = \CVS\User::where('organizer', false)->where('id', '!=', $userWhoLooks->id)->first();

		$token = JWTAuth::fromUser($userWhoLooks);

		$this->get('/users/' . app('Optimus')->encode($userLooked->id), ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(401);
	}

	/**
	 * Getting a User from anonymous (should send 401)
	 */
	public function testCannotGetUserFromAno()
	{
		$user = \CVS\User::find(rand(1, 10));

		$this->get('/users/' . app('Optimus')->encode($user->id))
			->seeStatusCode(400);
	}

	/**
	 * Deleting a User from Organizer (should send 200)
	 */
	public function testCanDeleteUserFromOrganizer()
	{
		$organizer = factory(\CVS\User::class)->create([
			'organizer' => true
		]);

		$token = JWTAuth::fromUser($organizer);

		$user = \CVS\User::where('organizer', false)->first();

		$this->delete('/users/' . app('Optimus')->encode($user->id), [], ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200);
	}

	public function testCannotDeleteOrganizerFromOrganizer()
	{
		$organizer = factory(\CVS\User::class)->create([
			'organizer' => true
		]);

		$token = JWTAuth::fromUser($organizer);

		$user = \CVS\User::where('organizer', true)->first();

		$this->delete('/users/' . app('Optimus')->encode($user->id), [], ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(401);
	}

	/**
	 * Deleting an User from another one (should sent 400)
	 */
	public function testCannotDeleteUserFromUser()
	{
		$user = \CVS\User::orderByRaw("RAND()")->first();

		$this->delete('/users/' . app('Optimus')->encode($user->id))
			->seeStatusCode(400);
	}
}
