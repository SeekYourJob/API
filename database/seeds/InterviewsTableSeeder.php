<?php

use Illuminate\Database\Seeder;

class InterviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks = 0");
        DB::table('interviews')->truncate();

        // Creating interviews
        for ($i = 0; $i < 30; $i++) {

            do {
                $recruiter = CVS\Recruiter::whereId(rand(1, 15))->first();
                $candidate = CVS\Candidate::whereId(rand(1, 42))->first();
                $slot = CVS\Slot::whereId(rand(1, 8))->first();
            } while (!$this->validInterview($candidate, $recruiter, $slot));

               factory(CVS\Interview::class)->create([
                    'slot_id'=>$slot->id,
                    'candidate_id'=>$candidate->id,
                    'recruiter_id'=>$recruiter->id,
                    'company_id'=>$recruiter->company_id
               ]);
        }
    }

    private function validInterview(\CVS\Candidate $candidate, \CVS\Recruiter $recruiter, \CVS\Slot $slot)
    {
        //On vérifie si le candidat a deja un entretien de prévu a ce moment la
        if(CVS\Interview::where('candidate_id', $candidate->id)->where('slot_id', $slot->id)->exists()) {
            return false;
        }

        //On vérifie si le recruteur a deja un entretien de prévu a ce moment la
        if(CVS\Interview::where('recruiter_id', $recruiter->id)->where('slot_id', $slot->id)->exists()) {
            return false;
        }

        //On vérifie si le candidat et le recruteur n'ont pas deja un entretien ensemble
        if(CVS\Interview::where('recruiter_id', $recruiter->id)->where('candidate_id', $candidate->id)->exists()) {
            return false;
        }

        return true;
    }
}
