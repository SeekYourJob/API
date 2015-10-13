<?php

namespace CVS\Http\Controllers;

use CVS\Document;
use Illuminate\Http\Request;

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

//		$tmpFilename = storage_path('waiting_s3/' . $document->getFilename());
		// Moving file to internal folder instead of S3
		$document->move(storage_path('documents/' . $document->getClientOriginalName()));

//		$this->dispatch(new SendDocumentToS3($documentObject, $tmpFilename));

		if ($documentObject) {
			return response()->json(['id' => app('Optimus')->encode($documentObject->id), 'name' => $documentObject->name]);
		}

		abort(500);
	}
}