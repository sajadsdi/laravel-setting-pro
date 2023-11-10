<?php

namespace Sajadsdi\LaravelSettingPro\Contracts;

interface CacheDriverInterface
{
    public function __construct(array $config);

    public function get(string $key): mixed;

    public function set(string $key, mixed $data): void;

    public function clear($key): void;

    public function clearAll(): void;
}
