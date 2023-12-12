<?php
namespace Sajadsdi\LaravelSettingPro\Concerns;

use Closure;

trait SetCallbacksTrait
{
    /**
     * Get a callback function for set operation.
     *
     * @param string $setting Name of the setting.
     * @return Closure Callback function.
     */
    private function getCallbackSetOperation(string $setting): Closure
    {
        $class = $this;
        return function ($value, $key) use ($class, $setting) {
            $class->addToSet($setting, [$key => $value]);
        };
    }
}
