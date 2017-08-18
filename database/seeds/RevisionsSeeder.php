<?php

use Illuminate\Database\Seeder;

class RevisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Revision::class, 1)->create();
    }
}

