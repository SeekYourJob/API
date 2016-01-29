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

    //Adds files to archive
	public static function zipFiles($documents, $destination, $overwrite = false) {
		if (file_exists($destination) && !$overwrite)
			return false;
		if (count($documents)) {
			$zip = new ZipArchive();
			if ($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true)
				return false;
			foreach ($documents as $document) {
                $zip->addFile(Document::getDocumentFullPath($document),Document::getResumeFormattedBaseName($document));
            }
			$zip->close();

			return file_exists($destination);
		}
		return false;
	}

}
