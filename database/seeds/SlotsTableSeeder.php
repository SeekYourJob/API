<?php

use Illuminate\Database\Seeder;

class SlotsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('slots')->truncate();

        foreach (range(1, 10) as $key)
            factory(\CVS\Slot::class)->create();
    }
}
