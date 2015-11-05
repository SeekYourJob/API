<?php

namespace CVS;

use CVS\Traits\ObfuscatedIdTrait;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
	use ObfuscatedIdTrait;

    protected $guarded = ['id'];
	protected $appends = ['ido'];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function document()
	{
		return $this->belongsTo(Document::class, 'document_id');
	}
}
