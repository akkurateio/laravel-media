<?php

namespace Akkurate\LaravelMedia\Console;

use Illuminate\Console\Command;

class MediaSeed extends Command
{
    protected $signature = 'media:seed';
    protected $description = 'Seed the Media package from the config file';

    public function handle()
    {
        $this->call('db:seed', [
            '--class' => 'Akkurate\\LaravelMedia\\Database\\Seeders\\DatabaseSeeder'
        ]);
    }
}
