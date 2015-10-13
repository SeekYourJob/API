<?php namespace CVS\Traits;

trait ObfuscatedIdTrait
{
	public function getIdoAttribute()
	{
		return app('Optimus')->encode($this->id);
	}
}