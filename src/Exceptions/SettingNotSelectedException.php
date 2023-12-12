<?php

namespace Sajadsdi\LaravelSettingPro\Exceptions;

class SettingNotSelectedException extends \Exception
{
    public function __construct($operation = "")
    {
        parent::__construct("Please select setting name in '{$operation}' operation.");
    }
}
