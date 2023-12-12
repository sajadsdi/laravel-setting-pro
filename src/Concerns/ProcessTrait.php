<?php
namespace Sajadsdi\LaravelSettingPro\Concerns;

use Sajadsdi\LaravelSettingPro\Jobs\DeleteSettingJob;
use Sajadsdi\LaravelSettingPro\Jobs\UpdateSettingJob;

trait ProcessTrait
{
    private array        $sets     = [];
    private array        $deletes  = [];



    /**
     * Add key-value pairs to the set of changes to be saved.
     *
     * @param string $setting Name of the setting to add the key-value pairs to.
     * @param array $keyValue Key-value pairs to add to the set.
     * @return void
     */
    private function addToSet(string $setting, array $keyValue): void
    {
        $this->sets[$setting] = array_merge($this->sets[$setting] ?? [], $keyValue);
    }

    /**
     * remove pairs to the set of changes to be saved.
     *
     * @param string $settingName
     * @return void
     */
    private function removeFromSet(string $settingName): void
    {
        unset($this->sets[$settingName]);
    }

    /**
     * Add keys pairs to the delete operations.
     *
     * @param string $setting Name of the setting to add the key pairs to.
     * @param array $keys Keys pairs to add to the deletes.
     * @return void
     */
    private function addToDelete(string $setting, array $keys): void
    {
        $this->deletes[$setting] = array_merge($this->deletes[$setting] ?? [], $keys);
    }

    /**
     * Destructor for LaravelSettingPro class. Save changes to settings.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->deleteProcess();
        $this->settingProcess();
    }

    /**
     * Process changes to settings and save them.
     *
     * @return void
     */
    private function settingProcess(): void
    {
        foreach ($this->sets as $setting => $keyValue) {
            $updateParams = [
                $setting,
                $keyValue,
                $this->config['cache']['enabled'],
                $this->config['trigger_events'],
                $this->config['queue'],
            ];

            if ($this->config['process_to_queue']) {
                UpdateSettingJob::dispatch(...$updateParams);
            } else {
                UpdateSettingJob::dispatchSync(...$updateParams);
            }
        }
    }

    /**
     * Process delete on settings and save them.
     *
     * @return void
     */
    private function deleteProcess(): void
    {
        foreach ($this->deletes as $setting => $keys) {
            $deleteParams = [
                $setting,
                $keys,
                $this->config['cache']['enabled'],
                $this->config['trigger_events'],
                $this->config['queue'],
            ];

            if ($this->config['process_to_queue']) {
                DeleteSettingJob::dispatch(...$deleteParams);
            } else {
                DeleteSettingJob::dispatchSync(...$deleteParams);
            }
        }
    }
}
