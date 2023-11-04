<?php

namespace Sajadsdi\LaraSetting\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Lara Setting!';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        $this->info('Installing Lara Setting ...');
        $this->publish();
        $this->installMigrations();
        $config = config('lara-setting');
        $this->installSettingDirectory($config);
        $this->installTestSetting($config);
        $this->info('Installation completed !');
        $this->testSetting();
        return null;
    }
    
    private function publish()
    {
        $this->comment('Publishing configure ...');
        $this->call('vendor:publish',['--tag' => "lara-setting-configure"]);
        
        $this->comment('Publishing migration ...');
        $this->call('vendor:publish',['--tag' => "lara-setting-migration"]);
    }

    private function installSettingDirectory($config)
    {
        $this->comment('Creating setting directory ...');

        if(is_dir($config['store']['drivers']['file']['path'])){
            $this->warn('setting directory is exists ............ SKIPPED');
        }else{
            mkdir($config['store']['drivers']['file']['path'],0775);
            $this->info($config['store']['drivers']['file']['path'] . ' directory created ...... DONE');
        }
    }

    private function installTestSetting($config)
    {
        $this->comment('Creating test setting ...');
        if(file_exists($config['store']['drivers']['file']['path'].'test.php')){
            $this->warn('test.php is exists in setting directory ............ SKIPPED');
        }else {
            file_put_contents($config['store']['drivers']['file']['path'] . 'test.php',
                file_get_contents(__DIR__ . "/../../test/setting/test.php")
            );
            $this->info($config['store']['drivers']['file']['path'] . 'test.php created ....... DONE');
        }
    }

    private function installMigrations()
    {
        $this->comment('Migrating ...');
        $this->call('migrate',['--path' => "database/migrations/2023_11_03_030451_create_setting_table.php"]);
    }

    private function testSetting()
    {
        $this->comment("testing ...");
        $this->alert(setting('test','welcome') . " Ver:" . setting('test','version'));
    }
}
