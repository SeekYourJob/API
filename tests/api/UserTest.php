<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
	use DatabaseTransactions;

	/**
	 * A User should be able to logout
	 */
	public function testUserCanLogout()
	{
		$user = factory(\CVS\User::class)->create();

		$token = JWTAuth::fromUser($user);
		JWTAuth::invalidate($token);

		$this->get('/me', ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(401);
	}

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
	public function testCannotGetUsersFromBasicUser()
	{
		$user = factory(\CVS\User::class)->create([
			'organizer' => false
		]);

		$token = JWTAuth::fromUser($user);

		$this->get('/users', ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(403);
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
		$userIdObfuscated = app('Hashids')->encode($userId);

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
		$userIdObfuscated = app('Hashids')->encode($user->id);

		$token = JWTAuth::fromUser($user);

		$this->get("/users/$userIdObfuscated",  ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200)
			->seeJsonContains(['ido' => $userIdObfuscated]);
	}

	/**
	 * Getting a User from another one (should send 401)
	 */
	public function testCannotGetUserFromAnother()
	{
		$userWhoLooks = \CVS\User::where('organizer', false)->first();
		$userLooked = \CVS\User::where('organizer', false)->where('id', '!=', $userWhoLooks->id)->first();

		$token = JWTAuth::fromUser($userWhoLooks);

		$this->get('/users/' . app('Hashids')->encode($userLooked->id), ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(403);
	}

	/**
	 * Getting a User from anonymous (should send 401)
	 */
	public function testCannotGetUserFromAnonymous()
	{
		$user = \CVS\User::find(rand(1, 10));

		$this->get('/users/' . app('Hashids')->encode($user->id))
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

		$this->delete('/users/' . app('Hashids')->encode($user->id), [], ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200);
	}

	/** Deleting an Organizer from another (should throws error) */
	public function testCannotDeleteOrganizerFromOrganizer()
	{
		$organizer = factory(\CVS\User::class)->create([
			'organizer' => true
		]);

		$token = JWTAuth::fromUser($organizer);

		$user = \CVS\User::where('organizer', true)->first();

		$this->delete('/users/' . app('Hashids')->encode($user->id), [], ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(403);
	}

	/**
	 * Deleting an User from another one (should sent 400)
	 */
	public function testCannotDeleteUserFromUser()
	{
		$user = \CVS\User::orderByRaw("RAND()")->first();

		$this->delete('/users/' . app('Hashids')->encode($user->id))
			->seeStatusCode(400);
	}

	/**
	 * An Organizer car access Users' email addresses
	 */
	public function testOrganizerCanGetUsersEmailAddresses()
	{
		$organizer = factory(\CVS\User::class)->create([
			'organizer' => true
		]);

		$token = JWTAuth::fromUser($organizer);

		$this->get('/users/emails', ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200)
			->seeJson();
	}

	/**
	 * A basic User cannot access Users' email addresses
	 */
	public function testUserCannotGetUsersEmailAddresses()
	{
		$organizer = factory(\CVS\User::class)->create([
			'organizer' => false
		]);

		$token = JWTAuth::fromUser($organizer);

		$this->get('/users/emails', ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(403);
	}

	/**
	 * An anonymous cannot access Users' email addresses
	 */
	public function testAnonymousCannotGetUsersEmailAddresses()
	{
		$this->get('/users/emails')
			->seeStatusCode(400);
	}
}
