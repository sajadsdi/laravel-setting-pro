<?php
namespace Sajadsdi\LaravelSettingPro\Concerns;

use Closure;

trait DeleteCallbacksTrait
{
    /**
     * Get a callback function for delete operation.
     *
     * @param string $setting Name of the setting.
     * @return Closure Callback function.
     */
    private function getCallbackDeleteOperation(string $setting): Closure
    {
        $class = $this;
        return function ($key) use ($class, $setting) {
            $class->addToDelete($setting, [$key]);

            unset($class->sets[$setting][$key]);
            if (!$class->sets[$setting]) {
                $this->removeFromSet($setting);
            }
        };
    }
}
