<?php

namespace CVS;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
	protected $table = 'locations';
	protected $guarded = ['id'];
	protected $hidden = [];
}
