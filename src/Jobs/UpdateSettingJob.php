<?php

namespace Sajadsdi\LaraSetting\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Sajadsdi\ArrayDotNotation\Traits\MultiDotNotationTrait;
use Sajadsdi\LaraSetting\Events\UpdateSettingEvent;
use Sajadsdi\LaraSetting\Services\SettingStore;

class UpdateSettingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, MultiDotNotationTrait;


    /**
     * Create a new job instance.
     */
    public function __construct(public string $settingName, public array $keyValue, public bool $cacheEnabled,public bool $triggerEvent, string $queue)
    {
        $this->onQueue($queue);
    }

    /**
     * Execute the job.
     */
    public function handle(SettingStore $store): void
    {
        if ($this->cacheEnabled) {
            $store->cache()->clear($this->settingName);
        }

        $oldData = $store->get($this->settingName) ?? [];

        $store->set($this->settingName, $this->setByDotMulti($oldData, $this->keyValue));

        if ($this->triggerEvent) {
            UpdateSettingEvent::dispatch($this->settingName, $this->keyValue);
        }
    }
}
