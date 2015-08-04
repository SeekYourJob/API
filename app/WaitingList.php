<?php

namespace CVS;

use Illuminate\Database\Eloquent\Model;

class WaitingList extends Model
{
	protected $table = 'waiting_list';
	protected $guarded = ['id'];
}
