<?php
namespace Sajadsdi\LaravelSettingPro\Concerns;

use Closure;

trait GetCallbacksTrait
{
    /**
     * Get a callback function for default value operation.
     *
     * @param string $setting Name of the setting.
     * @return Closure Callback function.
     */
    private function getCallbackDefaultValueOperation(string $setting): Closure
    {
        $class = $this;
        return function ($default, $key) use ($class, $setting) {
            $class->set($setting, [$key => $default]);
        };
    }
}
