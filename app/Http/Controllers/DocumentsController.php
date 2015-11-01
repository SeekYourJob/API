<?php

namespace CVS\Http\Controllers;

use CVS\Document;
use CVS\User;
use CVS\Recruiter;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function __construct()
    {
    }

    public function create(Request $request)
    {
        $file = $request->file('file');
        $data = $request->get('data');

        $documentObject = Document::create([
            'name' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getClientSize(),
            'size_readable' => Document::getReadableFilesize($file->getClientSize())
        ]);

        if(array_key_exists('user',$data)) {
          //  \Log::info('USER FOUND :'.$data['user']);
          //  \Log::info('USER FOUND DECODED:'.app('Optimus')->decode(1646891037));
            for($i=0; $i<=16; $i++){
                $test = $i;
                $encodedTest = app('Optimus')->encode($test);
                \Log::info('TEST:'.$test.'=>'.$encodedTest.'=>'.app('Optimus')->decode($encodedTest));
            }


            //$user = User::whereId(app('Optimus')->decode($data['user']))->firstOrFail();
        }

        $file->move(storage_path('documents/' . app('Optimus')->encode($documentObject->id)));

        if ($documentObject) {
            return response()->json(['id' => app('Optimus')->encode($documentObject->id), 'name' => $documentObject->name]);
        }

        abort(500);
    }

    public function getFile(Document $document){
        $file= storage_path('documents/' . app('Optimus')->encode($document->id));
        $headers = array(
            'Content-Type: application/'.$document->extension,
        );
        return Response::download($file, $document->name.'.'.$document->extension, $headers);
    }
}