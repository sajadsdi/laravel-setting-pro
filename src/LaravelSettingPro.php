<?php

namespace Sajadsdi\LaravelSettingPro;

use Sajadsdi\ArrayDotNotation\Exceptions\ArrayKeyNotFoundException;
use Sajadsdi\ArrayDotNotation\Traits\MultiDotNotationTrait;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingKeyNotFoundException;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingNotFoundException;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingNotSelectedException;
use Sajadsdi\LaravelSettingPro\Jobs\UpdateSettingJob;
use Sajadsdi\LaravelSettingPro\Services\SettingStore;

class LaravelSettingPro
{
    private array        $settings = [];
    private array        $sets     = [];
    private SettingStore $store;
    private array        $config   = [];
    use MultiDotNotationTrait;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->store  = app()->make(SettingStore::class);
    }

    /**
     * @param string $settingName
     * @param mixed|null $Keys
     * @param mixed|null $default
     * @return mixed
     * @throws SettingKeyNotFoundException
     * @throws SettingNotFoundException
     * @throws SettingNotSelectedException
     */
    public function get(string $settingName, mixed $Keys = '', mixed $default = null): mixed
    {
        $this->load($settingName, 'get');
        try {
            return $this->getByDotMulti($this->getSetting($settingName), is_array($Keys) ? $Keys : [$Keys], $default, $this->getCallbackDefaultValueOperation($settingName));
        } catch (ArrayKeyNotFoundException $exception) {
            if ($this->settings[$settingName]) {
                throw new SettingKeyNotFoundException($exception->key, $exception->keysPath, $settingName);
            } else {
                throw new SettingNotFoundException($settingName);
            }
        }
    }

    /**
     * @param string $settingName
     * @param array $keyValue
     * @return void
     * @throws SettingNotSelectedException
     */
    public function set(string $settingName, array $keyValue): void
    {
        $this->load($settingName, 'set');
        $this->setSetting($settingName, $this->setByDotMulti($this->getSetting($settingName), $keyValue));
        $this->addToSet($settingName, $keyValue);
    }

    /**
     * @param string $setting
     * @param string $operation
     * @return void
     * @throws SettingNotSelectedException
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
     * @param string $name
     * @param mixed $data
     * @return void
     */
    private function setSetting(string $name, mixed $data): void
    {
        $this->settings[$name] = $data;
    }

    /**
     * @param string $name
     * @return mixed
     */
    private function getSetting(string $name): mixed
    {
        return $this->settings[$name] ?? [];
    }

    private function getCallbackDefaultValueOperation(string $setting): \Closure
    {
        $class = $this;
        return function ($key, $default, $item) use ($class, $setting) {
            $class->set($setting, [$key => $default]);
        };
    }

    private function addToSet(string $setting, $keyValue)
    {
        $this->sets[$setting] = array_merge($this->sets[$setting] ?? [], $keyValue);
    }

    public function __destruct()
    {
        $this->settingProcess();
    }

    private function settingProcess()
    {
        foreach ($this->sets as $setting => $keyValue) {
            $updateParams = [
                $setting,
                $keyValue,
                $this->config['cache']['enabled'],
                $this->config['trigger_event'],
                $this->config['queue'],
            ];
            if ($this->config['background_job']) {
                UpdateSettingJob::dispatch(...$updateParams);
            } else {
                UpdateSettingJob::dispatchSync(...$updateParams);
            }
        }
    }

}
