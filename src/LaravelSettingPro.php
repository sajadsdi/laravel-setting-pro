<?php

namespace Sajadsdi\LaravelSettingPro;

use Closure;
use Sajadsdi\ArrayDotNotation\Exceptions\ArrayKeyNotFoundException;
use Sajadsdi\ArrayDotNotation\Traits\MultiDotNotationTrait;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingKeyNotFoundException;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingNotFoundException;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingNotSelectedException;
use Sajadsdi\LaravelSettingPro\Jobs\UpdateSettingJob;
use Sajadsdi\LaravelSettingPro\Services\SettingStore;

/**
 * Class LaravelSettingPro
 * This Class provides methods to get and set settings using array dot notation.
 * @package Sajadsdi\LaravelSettingPro\LaravelSettingPro
 */
class LaravelSettingPro
{
    private array $settings = [];
    private array $sets = [];
    private SettingStore $store;
    private array $config;

    use MultiDotNotationTrait;

    /**
     * Constructor for Laravel Setting Pro class.
     *
     * @param SettingStore $store Instance of SettingStore for storing settings.
     */
    public function __construct(SettingStore $store)
    {
        $this->config = config('laravel-setting');
        $this->store = $store;
    }

    /**
     * Get the value of a setting using array dot notation.
     *
     * @param string $settingName Name of the setting to get.
     * @param mixed|null $Keys Keys to access nested values in the setting.
     * @param mixed|null $default Default value to return if the setting or key is not found.
     * @return mixed Value of the setting.
     * @throws SettingKeyNotFoundException If the specified key is not found in the setting.
     * @throws SettingNotFoundException If the specified setting is not found.
     * @throws SettingNotSelectedException If no setting is selected.
     */
    public function get(string $settingName, mixed $Keys = '', mixed $default = null): mixed
    {
        $this->load($settingName, 'get');

        try {
            return $this->getByDotMulti(
                $this->getSetting($settingName),
                is_array($Keys) ? $Keys : [$Keys],
                $default,
                $this->getCallbackDefaultValueOperation($settingName)
            );
        } catch (ArrayKeyNotFoundException $exception) {
            if ($this->settings[$settingName]) {
                throw new SettingKeyNotFoundException($exception->key, $exception->keysPath, $settingName);
            } else {
                throw new SettingNotFoundException($settingName);
            }
        }
    }

    /**
     * Set the value of a setting using array dot notation.
     *
     * @param string $settingName Name of the setting to set.
     * @param array $keyValue Associative array of keys and values to set in the setting.
     * @return void
     * @throws SettingNotSelectedException If no setting is selected.
     */
    public function set(string $settingName, array $keyValue): void
    {
        $this->load($settingName, 'set');
        $this->setSetting($settingName, $this->setByDotMulti($this->getSetting($settingName), $keyValue));
        $this->addToSet($settingName, $keyValue);
    }

    /**
     * Load a setting and validate that it exists.
     *
     * @param string $setting Name of the setting to load.
     * @param string $operation Name of the operation being performed (get or set).
     * @return void
     * @throws SettingNotSelectedException If no setting is selected.
     */
    private function load(string $setting, string $operation): void
    {
        if (!$setting) {
            throw new SettingNotSelectedException($operation);
        }
        if (!isset($this->settings[$setting])) {
            $this->setSetting($setting, $this->store->get($setting) ?? []);
        }
    }

    /**
     * Set the value of a setting.
     *
     * @param string $name Name of the setting to set.
     * @param mixed $data Value to set in the setting.
     * @return void
     */
    private function setSetting(string $name, mixed $data): void
    {
        $this->settings[$name] = $data;
    }

    /**
     * Get the value of a setting.
     *
     * @param string $name Name of the setting to get.
     * @return mixed Value of the setting.
     */
    private function getSetting(string $name): mixed
    {
        return $this->settings[$name] ?? [];
    }

    /**
     * Get a callback function to set a default value for a key in a setting.
     *
     * @param string $setting Name of the setting to set the default value in.
     * @return Closure Callback function.
     */
    private function getCallbackDefaultValueOperation(string $setting): Closure
    {
        $class = $this;
        return function ($key, $default) use ($class, $setting) {
            $class->set($setting, [$key => $default]);
        };
    }

    /**
     * Add key-value pairs to the set of changes to be saved.
     *
     * @param string $setting Name of the setting to add the key-value pairs to.
     * @param mixed $keyValue Key-value pairs to add to the set.
     * @return void
     */
    private function addToSet(string $setting, $keyValue): void
    {
        $this->sets[$setting] = array_merge($this->sets[$setting] ?? [], $keyValue);
    }

    /**
     * Destructor for LaravelSettingPro class. Save changes to settings.
     *
     * @return void
     */
    public function __destruct()
    {
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
                $this->config['trigger_event'],
                $this->config['queue'],
            ];

            if ($this->config['process_to_queue']) {
                UpdateSettingJob::dispatch(...$updateParams);
            } else {
                UpdateSettingJob::dispatchSync(...$updateParams);
            }
        }
    }
}
