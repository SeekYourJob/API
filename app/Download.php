<?php

namespace CVS;

use CVS\Traits\ObfuscatedIdTrait;
use Illuminate\Database\Eloquent\Model;
use Storage;
use ZipArchive;

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

	public static function zipFiles($files, $destination, $overwrite = false) {
		if (file_exists($destination) && !$overwrite)
			return false;

		if (is_array($files) && count($files)) {
			$zip = new ZipArchive();
			if ($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true)
				return false;

			foreach ($files as $filePath => $fileOriginalName)
				$zip->addFile($filePath, $fileOriginalName);

			$zip->close();
			return file_exists($destination);
		}

		return false;
	}
}
