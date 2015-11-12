<?php

namespace CVS\Http\Controllers;

use Auth;
use CVS\Download;
use Input;
use Response;
use CVS\Document;
use CVS\User;
use CVS\Recruiter;
use Illuminate\Http\Request;


class DocumentsController extends Controller
{
    public function __construct()
    {
		$this->middleware('jwt.auth', ['except' => ['create', 'getFile']]);
    }

    public function create(Request $request)
    {
        $document = Input::file('file');
        $data = $request->get('data');

        $documentObject = Document::create([
            'name' => $document->getClientOriginalName(),
            'extension' => $document->getClientOriginalExtension(),
            'size' => $document->getClientSize(),
            'size_readable' => Document::getReadableFilesize($document->getClientSize())
        ]);

        if (is_array($data) && array_key_exists('user', $data)) {
            $user = User::whereId(app('Hashids')->decode($data['user'])[0])->firstOrFail();

            if($user->profile_type == 'CVS\Candidate') {
                foreach ($user->documents as $document) {
                    $document->dissociate();
                    $document->save();
                }
            }

            $user->documents()->save($documentObject);
        }

        $info = $document->move(storage_path('documents/'),$documentObject->ido);

        if ($documentObject) {
            return response()->json(['ido' => $documentObject->ido, 'name' => $documentObject->name]);
        }

        abort(500);
    }

    public function getFilesForUser(User $user)
    {
        $this->authorize('show-user', $user);
        $response = [];
        foreach ($user->documents as $document) {
            $response[] = [
                'ido' => $document->ido,
                'name' => $document->name,
                'status' => $document->status
            ];
        }

        return  response()->json($response);

    }

    public function deleteFile(Document $document)
    {
        if($document->user == null) {
            abort(400);
        }
        $this->authorize('show-user', $document->user);

        $document->user()->dissociate();
        $document->save();
        return response('');
    }

    public function getRequestTokenForDocument(Document $document)
    {
        $authenticatedUser = Auth::user();

        $download = Download::create([
            'user_id' => $authenticatedUser->id,
            'document_id' => $document->id,
            'url' => str_random(42),
        ]);

        return response()->json(['token' => $download->url]);
    }

    public function getFile($requestToken)
    {
        $download = Download::whereUrl($requestToken)->first();

        if ($download->viewed)
            abort(403, "Download link expired.");

        $file = storage_path('documents/' . $download->document->ido);
        $headers = ['Content-Type: application/' . $download->document->extension,];

        $download->viewed = true;
        $download->save();

        return response()->download($file, $download->document->name, $headers);
    }
}