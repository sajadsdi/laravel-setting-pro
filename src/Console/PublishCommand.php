<?php

namespace Sajadsdi\LaravelSettingPro\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Laravel Setting Pro configure and migration!';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        $this->info('Publishing Laravel Setting Pro ...');
        $this->publish();
        return null;
    }

    private function publish()
    {
        $this->comment('Publishing configure ...');
        $this->call('vendor:publish', ['--tag' => "laravel-setting-pro-configure"]);

        $this->comment('Publishing migration ...');
        $this->call('vendor:publish', ['--tag' => "laravel-setting-pro-migration"]);
    }
}
