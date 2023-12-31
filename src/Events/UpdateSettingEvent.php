<?php

namespace Sajadsdi\LaravelSettingPro\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UpdateSettingEvent
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(public string $settingName, public array $seatedKeyValues, public array $oldData)
    {
        //
    }
}
