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
	 * Create a Recruiter who belongs to a Company
	 */
	public function testCreateRecruiter()
	{
		$company = factory(CVS\Company::class)->create();
		$recruiter = factory(CVS\Recruiter::class)->create([
			'company_id' => $company->id
		]);

		$this->assertInstanceOf(CVS\Recruiter::class, $recruiter);
	}

	/**
	 * Create a User of profile Recruiter who belongs to a Company
	 */
	public function testCreateUserRecruiter()
	{
		$user = factory(CVS\User::class)->create();
		$company = factory(CVS\Company::class)->create();
		$recruiter = factory(CVS\Recruiter::class)->create([
			'company_id' => $company->id
		]);
		$recruiter->user()->save($user);

		// Checking the user is set in the Recruiter object
		$this->assertInstanceOf(CVS\User::class, $recruiter->user);

		// Checking the User's profile is the Recruiter object
		$this->assertInstanceOf(CVS\Recruiter::class, $user->profile);
	}

	/**
	 * Create a Company
	 */
	public function testCreateCompany()
	{
		$this->assertInstanceOf(CVS\Company::class, factory(CVS\Company::class)->create());
	}

	/**
	 * Create a Location
	 */
	public function testCreateLocation()
	{
		$this->assertInstanceOf(CVS\Location::class, factory(CVS\Location::class)->create());
	}

	/**
	 * Create a Slot
	 */
	public function testCreateSlots()
	{
		$this->assertInstanceOf(CVS\Slot::class, factory(CVS\Slot::class)->create());
	}

	/**
	 * Create an Interview
	 */
	public function testCreateInterviewsFree($withCandidate = false)
	{
		// Create the Company
		$company = factory(CVS\Company::class)->create();

		// Create the Recruiter (and its related User)
		$userRecruiter = factory(CVS\User::class)->create();
		$recruiter = factory(CVS\Recruiter::class)->create([
			'company_id' => $company->id
		]);
		$recruiter->user()->save($userRecruiter);

		// Create the Slot
		$slot = factory(CVS\Slot::class)->create();

		// The end is near: create the Interview
		$interview = new \CVS\Interview();
		$interview->company()->associate($company);
		$interview->slot()->associate($slot);
		$interview->recruiter()->associate($recruiter);

		if ($withCandidate)
		{
			// Create the Candidate (and its related User)
			$userCandidate = factory(CVS\User::class)->create();
			$candidate = factory(CVS\Candidate::class)->create();
			$candidate->user()->save($userCandidate);

			$interview->candidate()->associate($candidate);
		}

		$interview->save();

		// Testing Interview itself, then its components...
		$this->assertInstanceOf(CVS\Interview::class, $interview);
		$this->assertNotEmpty($interview->id);

		$this->assertInstanceOf(CVS\Slot::class, $interview->slot);
		$this->assertNotEmpty($interview->slot->id);

		$this->assertInstanceOf(CVS\Company::class, $interview->company);
		$this->assertNotEmpty($interview->company->id);

		$this->assertInstanceOf(CVS\Recruiter::class, $interview->recruiter);
		$this->assertNotEmpty($interview->recruiter()->id);
	}
}
