<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiTest extends TestCase
{
	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testGetTest()
	{
		$this->get('/test')
			->seeStatusCode(200);
	}
}
