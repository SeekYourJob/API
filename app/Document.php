<?php

namespace CVS;

use CVS\Traits\ObfuscatedIdTrait;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
	use ObfuscatedIdTrait;

	protected $table = 'documents';
	protected $guarded = ['id'];
	protected $appends = ['ido'];

	public function profile()
	{
		return $this->morphTo();
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public static function getReadableFilesize($bytes, $decimals = 2)
	{
		$size = ['B','kB','MB','GB','TB','PB','EB','ZB','YB'];
		$factor = floor((strlen($bytes) - 1) / 3);

		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
	}

    public function dissociate()
    {
        return $this->user()->dissociate();
    }
}
