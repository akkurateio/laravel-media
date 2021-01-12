<?php

namespace Akkurate\LaravelMedia\Database\Seeders;

use Akkurate\LaravelMedia\Models\Type;
use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::create([
          'code' => 'DOC',
          'name' => 'Document',
          'description' => 'Documents: pdf, xls…',
          'priority' => 1,
        ]);
        Type::create([
          'code' => 'IMG',
          'name' => 'Image',
          'description' => 'Images: png, jpg, gif…',
          'priority' => 1,
        ]);
    }
}
