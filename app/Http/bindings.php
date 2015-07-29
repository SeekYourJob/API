<?php

use CVS\User;

Route::bind('user', function($value, $route)
{
	return User::findOrFail(app('Optimus')->decode($value));
});