<?php

namespace Sajadsdi\LaravelSettingPro\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Sajadsdi\ArrayDotNotation\Exceptions\ArrayKeyNotFoundException;
use Sajadsdi\ArrayDotNotation\Traits\MultiDotNotationTrait;
use Sajadsdi\LaravelSettingPro\Events\DeleteSettingEvent;
use Sajadsdi\LaravelSettingPro\Services\SettingStore;

class DeleteSettingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, MultiDotNotationTrait;


    /**
     * Create a new job instance.
     */
    public function __construct(public string $settingName, public array $keys, public bool $cacheEnabled, public bool $triggerEvent, string $queue)
    {
        $this->onQueue($queue);
    }

    /**
     * Execute the job.
     * @throws ArrayKeyNotFoundException
     */
    public function handle(SettingStore $store): void
    {
        $oldData = $store->getSetting($this->settingName) ?? [];
        $data = $oldData;

        if (!$this->keys) {
            $store->delete($this->settingName);
        } else {
            $store->set($this->settingName, $this->deleteByDotMulti($data, $this->keys));
        }

        if ($this->cacheEnabled) {
            $store->cache()->clear($this->settingName);
        }

        if ($this->triggerEvent) {
            DeleteSettingEvent::dispatch($this->settingName, $this->keys, $oldData);
        }
    }
}
