<?php

namespace CVS;

use CVS\Traits\ObfuscatedIdTrait;
use Illuminate\Database\Eloquent\Model;

class HistoryEmail extends Model
{
	protected $table = 'history_emails';
	protected $guarded = ['id'];
	protected $hidden = ['id', 'user_id', 'email', 'updated_at'];
	protected $appends = ['ido'];
	protected $casts = ['message' => 'array'];

	use ObfuscatedIdTrait;

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
