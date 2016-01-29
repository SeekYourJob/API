<?php

namespace CVS;

use CVS\Traits\ObfuscatedIdTrait;
use Illuminate\Database\Eloquent\Model;
use DB;
class Document extends Model
{
	use ObfuscatedIdTrait;

	protected $table = 'documents';
	protected $guarded = ['id'];
	protected $hidden = ['id', 'user_id', 'name_s3', 'size', 'created_at', 'updated_at'];
	protected $appends = ['ido'];

	public function profile()
	{
		return $this->morphTo();
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public static function getReadableFilesize($bytes, $decimals = 2)
	{
		$size = ['B','kB','MB','GB','TB','PB','EB','ZB','YB'];
		$factor = floor((strlen($bytes) - 1) / 3);

		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
	}

    public function dissociate()
    {
        return $this->user()->dissociate();
    }

    //Get candidate resumes to attach to email for recruiter
    public static function getCandidateDocumentsForRecruiter(Recruiter $recruiter)
    {
        $query = Document::join('users', 'documents.user_id', '=', 'users.id')
                    ->join('candidates', 'users.profile_id', '=', 'candidates.id')
                    ->join('interviews', 'interviews.candidate_id', '=', 'candidates.id')
                    ->select('documents.*')
                    ->where('interviews.recruiter_id', $recruiter->id)
                    ->where('documents.status','ACCEPTED')
                    ->get();
        return $query;
    }

    public static function getDocumentFullPath(Document $document)
    {
        return storage_path('documents/' . $document->ido);
    }

    //Rename resume before attaching to email
    public static function getResumeFormattedBaseName(Document $document)
    {
        return preg_replace('/[^a-zA-Z0-9._]/', '', 'CV_'.$document->user->firstname.'_'.$document->user->lastname.'.'.$document->extension);
    }

}
