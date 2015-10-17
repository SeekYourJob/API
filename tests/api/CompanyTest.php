<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
	use DatabaseTransactions;

	/**
	 * A recruiter from a Company could access its recruiters
	 */
	public function testCompanyCanAccessItsRecruiters()
	{
		$user = factory(\CVS\User::class)->create();
		$company = factory(\CVS\Company::class)->create();

		$recruiter = factory(\CVS\Recruiter::class)->create([
			'company_id' => $company->id
		]);
		$recruiter->user()->save($user);

		$token = JWTAuth::fromUser($user);

		$this->get('/companies/' . $company->ido .  '/recruiters', ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200)
			->seeJson();
	}

	/**
	 * An Organizer can access a Company's Recruiters
	 */
	public function testOrganizerCanAccessCompanyRecruiters()
	{
		$user = factory(\CVS\User::class)->create([
			'organizer' => true
		]);
		$company = factory(\CVS\Company::class)->create();

		$token = JWTAuth::fromUser($user);

		$this->get('/companies/' . $company->ido .  '/recruiters', ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200)
			->seeJson();
	}

	public function testCompanyCannotAccessOtherCompanyRecruiters()
	{
		$user = factory(\CVS\User::class)->create([
			'organizer' => false
		]);
		$company = factory(\CVS\Company::class)->create();
		$recruiter = factory(\CVS\Recruiter::class)->create([
			'company_id' => $company->id
		]);
		$recruiter->user()->save($user);

		$user1 = factory(\CVS\User::class)->create([
			'organizer' => false
		]);
		$company1 = factory(\CVS\Company::class)->create();
		$recruiter1 = factory(\CVS\Recruiter::class)->create([
			'company_id' => $company1->id
		]);
		$recruiter1->user()->save($user1);

		$token = JWTAuth::fromUser($user);

		$this->get('/companies/' . $company1->ido .  '/recruiters', ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(403);
	}

	/**
	 * A login is required to access a company recruiters
	 */
	public function testAnyoneCannotAccessCompanyRecruiters()
	{
		$user = factory(\CVS\User::class)->create([
			'organizer' => false
		]);
		$company = factory(\CVS\Company::class)->create();
		$recruiter = factory(\CVS\Recruiter::class)->create([
			'company_id' => $company->id
		]);
		$recruiter->user()->save($user);

		$this->get('/companies/' . $company->ido .  '/recruiters')
			->seeStatusCode(400);
	}

	/**
	 * An Organizer can access all companies
	 */
	public function testOrganizerCanAccessCompanies()
	{
		$user = factory(\CVS\User::class)->create([
			'organizer' => true
		]);

		$token = JWTAuth::fromUser($user);

		$this->get('/companies/', ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200)
			->seeJson();
	}

	/**
	 * Anonymous cannont access Companies
	 */
	public function testAnonymousCannotAccessCompanies()
	{
		$this->get('/companies/')
			->seeStatusCode(400);
	}

	/**
	 * Lambda users cannot access companies
	 */
	public function testUsersCannotAccessCompanies()
	{
		$user = factory(\CVS\User::class)->create([
			'organizer' => false
		]);

		$token = JWTAuth::fromUser($user);

		$this->get('/companies/', ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(403);
	}

	/**
	 * Organizer can access details of one company
	 */
	public function testOrganizerCanAccessOneCompany()
	{
		$user = factory(\CVS\User::class)->create([
			'organizer' => true
		]);
		$company = factory(\CVS\Company::class)->create();
		$recruiter = factory(\CVS\Recruiter::class)->create([
			'company_id' => $company->id
		]);
		$recruiter->user()->save($user);

		$token = JWTAuth::fromUser($user);

		$this->get('/companies/' . $company->ido, ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200)
			->seeJson();
	}

	/**
	 * Member of a Company can access its details
	 */
	public function testMemberOfCompanyCanAccessItsCompany()
	{
		$user = factory(\CVS\User::class)->create([
			'organizer' => false
		]);
		$company = factory(\CVS\Company::class)->create();
		$recruiter = factory(\CVS\Recruiter::class)->create([
			'company_id' => $company->id
		]);
		$recruiter->user()->save($user);

		$token = JWTAuth::fromUser($user);

		$this->get('/companies/' . $company->ido, ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(200)
			->seeJson();
	}

	/**
	 * Member of a Company cannot access another Company's details
	 */
	public function testMemberOfCompanyCannotAccessAnotherCompany()
	{
		$user = factory(\CVS\User::class)->create([
			'organizer' => false
		]);
		$company = factory(\CVS\Company::class)->create();
		$recruiter = factory(\CVS\Recruiter::class)->create([
			'company_id' => $company->id
		]);
		$recruiter->user()->save($user);

		$user1 = factory(\CVS\User::class)->create([
			'organizer' => false
		]);
		$company1 = factory(\CVS\Company::class)->create();
		$recruiter1 = factory(\CVS\Recruiter::class)->create([
			'company_id' => $company1->id
		]);
		$recruiter->user()->save($user1);

		$token = JWTAuth::fromUser($user);

		$this->get('/companies/' . $company1->ido, ['HTTP_AUTHORIZATION' => "Bearer $token"])
			->seeStatusCode(403);
	}

	/**
	 * Anonymous user cannot access a Company details
	 */
	public function testAnonymousCannotAccessCompany()
	{
		$user = factory(\CVS\User::class)->create([
			'organizer' => false
		]);
		$company = factory(\CVS\Company::class)->create();
		$recruiter = factory(\CVS\Recruiter::class)->create([
			'company_id' => $company->id
		]);
		$recruiter->user()->save($user);

		$this->get('/companies/' . $company->ido)
			->seeStatusCode(400);
	}
}
