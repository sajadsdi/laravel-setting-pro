<?php

namespace Sajadsdi\LaravelSettingPro\Console;

use Illuminate\Console\Command;

class PublishMongoDBCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:publish-mongodb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Laravel Setting Pro migration for MongoDB!';

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
        $this->comment('Publishing migration for mongoDB...');
        $this->call('vendor:publish', ['--tag' => "laravel-setting-pro--mongodb-migration"]);
    }
}
