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
        DB::statement("SET foreign_key_checks = 0");
        DB::table('users')->truncate();

        // Creating candidates
        for ($i = 0; $i < 42; $i++) {
            $user = factory(\CVS\User::class)->create(['email' => 'candidate' . rand(0, 999999) . '@test.com']);
            $candidate = factory(CVS\Candidate::class)->create();
            $candidate->user()->save($user);
        }

        // Creating recruiters
        for ($i = 0; $i < 15; $i++) {
            $company = factory(CVS\Company::class)->create();
            for ($j = 0; $j < rand(1, 4); $j++) {
                $user = factory(\CVS\User::class)->create(['email' => 'recruiter' . rand(0, 999999) . '@test.com']);
                $recruiter = factory(CVS\Recruiter::class)->create(['company_id' => $company->id]);
                $recruiter->user()->save($user);
            }
        }
    }
}
