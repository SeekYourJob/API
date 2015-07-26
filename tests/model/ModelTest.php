<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ModelTest extends TestCase
{
	public function testCreateUser()
	{
		$this->assertInstanceOf(CVS\User::class, factory(CVS\User::class)->make());
	}
}
