<?php

namespace Sajadsdi\LaravelSettingPro\Contracts;

interface DatabaseDriverInterface
{
    public function getSetting(string $key): mixed;

    public function setSetting(string $key, mixed $data): void;

    public function deleteSetting(string $key): void;
}
