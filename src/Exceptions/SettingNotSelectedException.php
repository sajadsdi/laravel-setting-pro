<?php

namespace Sajadsdi\LaravelSettingPro\Exceptions;

class SettingNotSelectedException extends \Exception
{
    public function __construct($operation)
    {
        $message = "Please select setting name in '$operation' operation.";
        parent::__construct($message);
    }
}
