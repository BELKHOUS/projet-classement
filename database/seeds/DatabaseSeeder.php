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
        $repository->addUser('walid@example.com', 'secret1');
        $repository->addUser('lyes@example.com', 'secret2');
        $repository->addUser('nabil@example.com', 'secret3');
    }

}
