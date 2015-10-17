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

        factory(\CVS\Slot::class)->create(['begins_at' => '2015-02-12 09:00:00', 'ends_at' => '2015-02-12 09:45:00', 'availability' => 'AM']);
        factory(\CVS\Slot::class)->create(['begins_at' => '2015-02-12 09:45:00', 'ends_at' => '2015-02-12 10:30:00', 'availability' => 'AM']);
        factory(\CVS\Slot::class)->create(['begins_at' => '2015-02-12 10:30:00', 'ends_at' => '2015-02-12 11:15:00', 'availability' => 'AM']);
        factory(\CVS\Slot::class)->create(['begins_at' => '2015-02-12 11:15:00', 'ends_at' => '2015-02-12 12:00:00', 'availability' => 'AM']);
        factory(\CVS\Slot::class)->create(['begins_at' => '2015-02-12 14:00:00', 'ends_at' => '2015-02-12 14:45:00', 'availability' => 'PM']);
        factory(\CVS\Slot::class)->create(['begins_at' => '2015-02-12 14:45:00', 'ends_at' => '2015-02-12 15:30:00', 'availability' => 'PM']);
        factory(\CVS\Slot::class)->create(['begins_at' => '2015-02-12 15:30:00', 'ends_at' => '2015-02-12 16:15:00', 'availability' => 'PM']);
        factory(\CVS\Slot::class)->create(['begins_at' => '2015-02-12 16:15:00', 'ends_at' => '2015-02-12 17:00:00', 'availability' => 'PM']);
    }
}
