<?php

namespace Sajadsdi\LaravelSettingPro;


use Sajadsdi\ArrayDotNotation\Exceptions\ArrayKeyNotFoundException;
use Sajadsdi\ArrayDotNotation\Traits\MultiDotNotationTrait;
use Sajadsdi\LaravelSettingPro\Concerns\DeleteCallbacksTrait;
use Sajadsdi\LaravelSettingPro\Concerns\GetCallbacksTrait;
use Sajadsdi\LaravelSettingPro\Concerns\ProcessTrait;
use Sajadsdi\LaravelSettingPro\Concerns\SetCallbacksTrait;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingKeyNotFoundException;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingNotFoundException;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingNotSelectedException;
use Sajadsdi\LaravelSettingPro\Services\SettingStore;

/**
 * Class LaravelSettingPro
 * This Class provides methods to get and set settings using array dot notation.
 * @package Sajadsdi\LaravelSettingPro\LaravelSettingPro
 */
class LaravelSettingPro
{
    private array        $settings = [];
    private SettingStore $store;
    private array        $config;

    use ProcessTrait, MultiDotNotationTrait, GetCallbacksTrait, SetCallbacksTrait, DeleteCallbacksTrait;

    /**
     * Constructor for Laravel Setting Pro class.
     *
     * @param SettingStore $store Instance of SettingStore for storing settings.
     */
    public function __construct(array $config, SettingStore $store)
    {
        $this->config = $config;
        $this->store  = $store;
    }

    /**
     * Get the value of a setting using array dot notation.
     *
     * @param string $settingName Name of the setting to get.
     * @param mixed $Keys Keys to access nested values in the setting.
     * @param mixed|null $default Default value to return if the setting or key is not found.
     * @param bool $throw flag to disable 'NotFound' exceptions
     * @return mixed Value of the setting.
     *
     * @throws SettingKeyNotFoundException If the specified key is not found in the setting.
     * @throws SettingNotFoundException If the specified setting is not found.
     * @throws SettingNotSelectedException If no setting is selected.
     */
    public function get(string $settingName, mixed $Keys = [], mixed $default = null, bool $throw = true): mixed
    {
        $this->load($settingName, 'get');

        try {

            return $this->getByDotMulti(
                $this->getSetting($settingName),
                $this->getArrayKeys($Keys), $default,
                $this->getCallbackDefaultValueOperation($settingName)
            );

        } catch (ArrayKeyNotFoundException $exception) {

            if ($throw) {
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
     *
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
     *
     * @throws ArrayKeyNotFoundException
     * @throws SettingNotSelectedException If no setting is selected.
     */
    public function delete(string $settingName, mixed $keys = []): void
    {
        $this->load($settingName, 'delete');

        $aKeys = $this->getArrayKeys($keys);

        if (!$aKeys) {
            $this->setSetting($settingName, []);
            $this->addToDelete($settingName, $aKeys);
            $this->removeFromSet($settingName);
        } else {
            $this->deleteByDotMulti($this->settings[$settingName], $aKeys, false, $this->getCallbackDeleteOperation($settingName));
        }
    }

    /**
     * Has key(S) on selected setting.
     *
     * @param string $settingName Name of the setting.
     * @param mixed $keys to check has exists on setting.
     * @return bool
     *
     * @throws SettingNotSelectedException
     */
    public function has(string $settingName, mixed $keys = []): bool
    {
        $this->load($settingName, 'has');

        return $this->issetAll($this->getSetting($settingName), $this->getArrayKeys($keys));
    }

    /**
     * Load a setting and validate that it exists.
     *
     * @param string $setting Name of the setting to load.
     * @param string $operation Name of the operation being performed (get or set or delete).
     * @return void
     *
     * @throws SettingNotSelectedException If no setting is selected.
     */
    private function load(string $setting, string $operation): void
    {
        if (!$setting) {
            throw new SettingNotSelectedException($operation);
        }

        if ($this->isSetSetting($setting)) {
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
        return $this->settings[$name] ?? $this->settings[$name] = [];
    }

    /**
     * check Setting name is seated.
     * @param string $setting
     * @return bool
     */
    private function isSetSetting(string $setting): bool
    {
        return !isset($this->settings[$setting]);
    }
}
