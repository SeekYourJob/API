<?php namespace CVS\Traits;

trait ObfuscatedIdTrait
{
	public function getIdoAttribute()
	{
		return app('Hashids')->encode($this->id);
	}
}