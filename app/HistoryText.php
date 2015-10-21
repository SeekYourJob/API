<?php

namespace CVS;

use CVS\Traits\ObfuscatedIdTrait;
use Illuminate\Database\Eloquent\Model;

class HistoryText extends Model
{
	protected $table = 'history_texts';
	protected $guarded = ['id'];
	protected $hidden = ['id', 'user_id', 'phone', 'updated_at'];
	protected $appends = ['ido'];
	protected $casts = ['ack' => 'array'];

	use ObfuscatedIdTrait;

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
