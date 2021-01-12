<?php

namespace Akkurate\LaravelMedia\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the Database Seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionsTableSeeder::class,
            TypesTableSeeder::class,
            ResourcesTableSeeder::class,
        ]);
    }
}
