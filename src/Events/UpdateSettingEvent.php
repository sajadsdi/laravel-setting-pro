<?php

namespace Sajadsdi\LaraSetting\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UpdateSettingEvent
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(public string $settingName, public array $keyValue)
    {
        //
    }

//    /**
//     * Get the channels the event should broadcast on.
//     *
//     * @return array<int, \Illuminate\Broadcasting\Channel>
//     */
//    public function broadcastOn(): array
//    {
//        return [
//            new PrivateChannel('channel-name'),
//        ];
//    }
}
