<?php

namespace Sajadsdi\LaravelSettingPro\Console;

use Illuminate\Console\Command;
use Sajadsdi\LaravelSettingPro\Services\SettingStore;

class ClearCacheCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Setting Caches!';

    /**
     * Execute the console command.
     *
     * @return null
     */
    public function handle(SettingStore $store)
    {
        $this->info('Clearing Setting Caches ...');
        if(config('_setting.cache.enabled')) {
            $store->cache()->clearAll();
            $this->info('Caches Cleared !');
        }else{
            $this->info('Setting cache not enabled. Clear skipped !');
        }
        return null;
    }

}
