<?php

namespace CVS\Http\Controllers;

use CVS\Document;
use CVS\Jobs\SendDocumentToS3;
use File;
use Illuminate\Http\Request;
use Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DocumentsController extends Controller
{
	public function __construct()
	{
//		$this->middleware('jws.auth', ['except' => ['create']]);
	}

	public function create(Request $request)
	{
		$document = $request->file('file');

		$documentObject = Document::create([
			'name' => $document->getClientOriginalName(),
			'name_s3' => strtolower(str_random(21)),
			'extension' => $document->getClientOriginalExtension(),
			'size' => $document->getClientSize(),
			'size_readable' => Document::getReadableFilesize($document->getClientSize())
		]);

		$tmpFilename = storage_path('waiting_s3/' . $document->getFilename());
		$document->move(storage_path('waiting_s3'));

		$this->dispatch(new SendDocumentToS3($documentObject, $tmpFilename));

		if ($documentObject) {
			return response()->json(['id' => app('Optimus')->encode($documentObject->id), 'name' => $documentObject->name]);
		}

		abort(500);
	}
}