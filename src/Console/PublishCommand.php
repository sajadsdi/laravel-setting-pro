<?php

namespace Sajadsdi\LaraSetting\Console;

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
    protected $description = 'Publish Lara setting configure and migration!';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        $this->info('Publishing Lara Setting ...');
        $this->publish();
        return null;
    }
    
    private function publish()
    {
        $this->comment('Publishing configure ...');
        $this->call('vendor:publish',['--tag' => "lara-setting-configure"]);
        
        $this->comment('Publishing migration ...');
        $this->call('vendor:publish',['--tag' => "lara-setting-migration"]);
    }
}
