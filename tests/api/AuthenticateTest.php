<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticateTest extends TestCase
{
	use DatabaseTransactions;

	/**
	 * Getting a token with valid credentials (should send 200 and the token)
	 */
	public function testCanGetTokenWithValidCredentials()
	{
		$user = factory(\CVS\User::class)->create();

		$this->post('authenticate', ['email' => $user->email, 'password' => 'password'])
			->seeStatusCode(200);
	}

	/**
	 * Getting a token without credentials (should send 401)
	 */
	public function testCannotGetTokenWithoutValidCredentials()
	{
		$this->post('authenticate', ['email' => '', 'password' => ''])
			->seeStatusCode(401);
	}
}
