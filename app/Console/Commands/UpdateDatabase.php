<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateDatabase extends Command
{
    protected $signature = 'db:update';
    protected $description = 'Run pending migrations automatically';

    public function handle()
    {
        $this->info('Running pending migrations...');
        \Artisan::call('migrate', ['--force' => true]);
        $this->info('Migrations completed.');
    }
}
