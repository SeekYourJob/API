<?php

namespace CVS\Jobs;

use CVS\Document;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class SendDocumentToS3 extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $document;
    public $documentPath;

    /**
     * @param Document $document
     * @param          $documentPath
     */
    public function __construct(Document $document, $documentPath)
    {
        $this->document = $document;
        $this->documentPath = $documentPath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (Storage::disk('s3')->put('uploads/' . $this->document->name_s3, file_get_contents($this->documentPath))) {
            \File::delete($this->documentPath);
        }
    }
}
