<?php

namespace Sajadsdi\LaravelSettingPro;

use Closure;
use Sajadsdi\ArrayDotNotation\Exceptions\ArrayKeyNotFoundException;
use Sajadsdi\ArrayDotNotation\Traits\MultiDotNotationTrait;
use Sajadsdi\LaravelSettingPro\Concerns\DeleteCallbacksTrait;
use Sajadsdi\LaravelSettingPro\Concerns\GetCallbacksTrait;
use Sajadsdi\LaravelSettingPro\Concerns\SetCallbacksTrait;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingKeyNotFoundException;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingNotFoundException;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingNotSelectedException;
use Sajadsdi\LaravelSettingPro\Jobs\DeleteSettingJob;
use Sajadsdi\LaravelSettingPro\Jobs\UpdateSettingJob;
use Sajadsdi\LaravelSettingPro\Services\SettingStore;

/**
 * Class LaravelSettingPro
 * This Class provides methods to get and set settings using array dot notation.
 * @package Sajadsdi\LaravelSettingPro\LaravelSettingPro
 */
class LaravelSettingPro
{
    private array        $settings = [];
    private array        $sets     = [];
    private array        $deletes  = [];
    private SettingStore $store;
    private array        $config;

    use MultiDotNotationTrait, GetCallbacksTrait, SetCallbacksTrait, DeleteCallbacksTrait;

    /**
     * Constructor for Laravel Setting Pro class.
     *
     * @param SettingStore $store Instance of SettingStore for storing settings.
     */
    public function __construct(SettingStore $store)
    {
        $this->config = config('_setting');
        $this->store  = $store;
    }

    /**
     * Get the value of a setting using array dot notation.
     *
     * @param string $settingName Name of the setting to get.
     * @param mixed|null $Keys Keys to access nested values in the setting.
     * @param mixed|null $default Default value to return if the setting or key is not found.
     * @param bool $throw flag to disable 'NotFound' exceptions
     * @return mixed Value of the setting.
     * @throws SettingKeyNotFoundException If the specified key is not found in the setting.
     * @throws SettingNotFoundException If the specified setting is not found.
     * @throws SettingNotSelectedException If no setting is selected.
     */
    public function get(string $settingName, mixed $Keys = '', mixed $default = null, bool $throw = true): mixed
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

            if($throw) {
                if ($this->settings[$settingName]) {
                    throw new SettingKeyNotFoundException($exception->key, $exception->keysPath, $settingName);
                } else {
                    throw new SettingNotFoundException($settingName);
                }
            }

        }

        return null;
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

        $this->setByDotMulti($this->settings[$settingName], $keyValue, $this->getCallbackSetOperation($settingName));
    }

    /**
     * Delete the keys of a setting using array dot notation.
     *
     * @param string $settingName Name of the setting to delete.
     * @param array|string|int $keys keys to delete in the setting.
     * @return void
     * @throws ArrayKeyNotFoundException
     * @throws SettingNotSelectedException If no setting is selected.
     */
    public function delete(string $settingName, array|string|int $keys = []): void
    {
        $this->load($settingName, 'delete');

        $aKeys = is_array($keys) ? $keys : (is_string($keys) && $keys ? [$keys] : (is_int($keys) ? [$keys] : []));

        if (!$aKeys) {
            $this->setSetting($settingName, []);
            $this->addToDelete($settingName, $aKeys);
            $this->removeFromSet($settingName);
        } else {
            $this->deleteByDotMulti($this->settings[$settingName], $aKeys, false, $this->getCallbackDeleteOperation($settingName));
        }
    }

    /**
     * Load a setting and validate that it exists.
     *
     * @param string $setting Name of the setting to load.
     * @param string $operation Name of the operation being performed (get or set or delete).
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
