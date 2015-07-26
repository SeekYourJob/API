<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ModelTest extends TestCase
{
	use DatabaseTransactions;

	/**
	 * Create a User
	 */
	public function testCreateUser()
	{
		$this->assertInstanceOf(CVS\User::class, factory(CVS\User::class)->create());
	}

	/**
	 * Create a Candidate
	 */
	public function testCreateCandidate()
	{
		$this->assertInstanceOf(CVS\Candidate::class, factory(CVS\Candidate::class)->create());
	}

	/**
	 * Create a User of profile Candidate
	 */
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

	/**
	 * Create a Recruiter
	 */
	public function testCreateRecruiter()
	{
		$this->assertInstanceOf(CVS\Recruiter::class, factory(CVS\Recruiter::class)->create());
	}

	/**
	 * Create a User of profile Recruiter
	 */
	public function testCreateUserRecruiter()
	{
		$user = factory(CVS\User::class)->create();
		$recruiter = factory(CVS\Recruiter::class)->create();
		$recruiter->user()->save($user);

		// Checking the user is set in the Recruiter object
		$this->assertInstanceOf(CVS\User::class, $recruiter->user);

		// Checking the User's profile is the Recruiter object
		$this->assertInstanceOf(CVS\Recruiter::class, $user->profile);
	}
}
