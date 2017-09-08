<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Revision::class, 5)->create();
        //factory(App\Revision::class, 5)->states('addRevision')->create();

    }
}
