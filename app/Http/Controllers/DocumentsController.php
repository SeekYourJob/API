<?php

namespace CVS\Http\Controllers;

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
		$this->middleware('jwt.auth', ['except' => ['create']]);
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

        if (array_key_exists('user', $data)) {
            $user = User::whereId(app('Hashids')->decode($data['user'])[0])->firstOrFail();
            $user->documents()->save($documentObject);
        }
        \Log::alert($document->getClientOriginalName());
        $info = $document->move(storage_path('documents/'),$documentObject->ido);

        if ($documentObject) {
            return response()->json(['id' => $documentObject->ido, 'name' => $documentObject->name]);
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
                'name' => $document->name
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

    public function getFile(Document $document)
    {
        $file = storage_path('documents/'.$document->ido);
        $headers = array(
            'Content-Type: application/' . $document->extension,
        );
        return Response::download($file, $document->name, $headers);
    }
}