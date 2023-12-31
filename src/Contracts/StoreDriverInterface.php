<?php

namespace Sajadsdi\LaravelSettingPro\Contracts;

interface StoreDriverInterface
{
    public function __construct(array $config);

    public function get(string $key): mixed;

    public function set(string $key, mixed $data): void;

    public function delete(string $key): void;
}
