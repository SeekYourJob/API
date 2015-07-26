<?php

namespace CVS;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
	protected $table = 'slots';
	protected $guarded = ['id'];
	protected $hidden = [];
}
