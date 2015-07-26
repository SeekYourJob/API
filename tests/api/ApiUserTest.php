<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiUserTest extends TestCase
{
	/**
	 * Getting all Users from an Organizer account (should send 200)
	 */
	public function testGetUsersFromOrganizer()
	{
		$user = factory(\CVS\User::class)->make([
			'organizer' => true
		]);

		$this->actingAs($user)
			->get('/api/users')
			->seeStatusCode(200)
			->seeJson();
	}

	/**
	 * Getting all Users from an account (should send 401)
	 */
	public function testGetUsersFromConnectedAccount()
	{
		$user = factory(\CVS\User::class)->make([
			'organizer' => false
		]);

		$this->actingAs($user)
			->get('/api/users')
			->seeStatusCode(401);
	}

	/**
	 * Getting all Users from anonymous (should sent 401)
	 */
	public function testGetUsersFromAnonymous()
	{
		$this->get('/api/users')
			->seeStatusCode(401);
	}

	/**
	 * Getting a specified User as Organizer (should send the User)
	 */
	public function testGetUserFromOrganizer()
	{
		$organizer = factory(\CVS\User::class)->make([
			'organizer' => true
		]);

		$userId = rand(1, 10);

		$this->actingAs($organizer)
			->get('/api/users/' . $userId)
			->seeStatusCode(200)
			->seeJsonContains(['id' => $userId]);
	}

	/**
	 * Getting a specified User as Organizer (should then the User)
	 */
	public function testGetUserFromHimself()
	{
		$user = \CVS\User::find(rand(1, 10));

		$this->actingAs($user)
			->get('/api/users/' . $user->id)
			->seeStatusCode(200)
			->seeJsonContains(['id' => $user->id]);
	}

	/**
	 * Getting a User from another one (should send 401)
	 */
	public function testGetUserFromAnotherOne()
	{
		$userWhoLooks = \CVS\User::find(rand(1, 5));
		$userLooked = \CVS\User::find(rand(5, 10));

		$this->actingAs($userWhoLooks)
			->get('/api/users/' . $userLooked->id)
			->seeStatusCode(401);
	}

	/**
	 * Getting a User from anonymous (should send 401)
	 */
	public function testGetUserFromAno()
	{
		$user = \CVS\User::find(rand(1, 10));

		$this->get('/api/users/' . $user->id)
			->seeStatusCode(401);
	}
}
