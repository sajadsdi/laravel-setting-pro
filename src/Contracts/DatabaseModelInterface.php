<?php

namespace Sajadsdi\LaravelSettingPro\Contracts;

interface DatabaseModelInterface
{
    public function getSetting(string $key): mixed;
    public function setSetting(string $key,mixed $data): void;
}
