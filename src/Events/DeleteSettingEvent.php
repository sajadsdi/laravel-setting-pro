<?php

namespace Sajadsdi\LaravelSettingPro\Events;

use Illuminate\Foundation\Events\Dispatchable;

class DeleteSettingEvent
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(public string $settingName, public array $deletedKeys, public array $oldData)
    {
        //
    }
}
