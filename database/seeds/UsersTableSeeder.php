<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        // Creating 5 candidates
        factory(\CVS\User::class, 5)->create()
            ->each(function($user) {
                $candidate = factory(CVS\Candidate::class)->create();
                $candidate->user()->save($user);
            });

        // Creating 5 recruiters
        factory(\CVS\User::class, 5)->create()
            ->each(function($user) {
                $company = factory(CVS\Company::class)->create();
                $recruiter = factory(CVS\Recruiter::class)->create([
                    'company_id' => $company->id
                ]);
                $recruiter->user()->save($user);
            });
    }
}
