<?php

namespace Sajadsdi\LaravelSettingPro\Exceptions;


class SettingKeyNotFoundException extends \Exception
{
    public function __construct(public $key, public $keysPath, public $settingName)
    {
        $message = "We can't find '{$this->key}' key" . ($this->keysPath ? " in '{$this->keysPath}*' path" : "") . " from '{$this->settingName}' setting .";
        parent::__construct($message);
    }
}
