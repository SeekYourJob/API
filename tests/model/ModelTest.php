<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ModelTest extends TestCase
{
	use DatabaseTransactions;

	public function testCreateUser()
	{
		$this->assertInstanceOf(CVS\User::class, factory(CVS\User::class)->create());
	}

	public function testCreateCandidate()
	{
		$this->assertInstanceOf(CVS\Candidate::class, factory(CVS\Candidate::class)->create());
	}

	public function testCreateUserCandidate()
	{
		$user = factory(CVS\User::class)->create();
		$candidate = factory(CVS\Candidate::class)->create();
		$candidate->user()->save($user);

		// Checking the user is set in the Candidate object
		$this->assertInstanceOf(CVS\User::class, $candidate->user);

		// Checking the User's profile is the Candidate object
		$this->assertInstanceOf(CVS\Candidate::class, $user->profile);
	}
}
