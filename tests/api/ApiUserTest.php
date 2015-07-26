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
		$user = factory(\CVS\User::class)->create([
			'organizer' => true
		]);

		$this->actingAs($user)
			->get('/api/users')
			->seeStatusCode(200);
	}

	/**
	 * Getting all Users from an account (should send 401)
	 */
	public function testGetUsersFromConnectedAccount()
	{
		$user = factory(\CVS\User::class)->create([
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
}
