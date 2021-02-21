<?php

use Illuminate\Database\Seeder;
use App\Repositories\Repository;
//use App\Repositories\Data;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        touch('database/database.sqlite');
        $repository = new Repository();
        $repository->createDatabase();
        $repository->fillDatabase();
        $repository->updateRanking();
    }

}
