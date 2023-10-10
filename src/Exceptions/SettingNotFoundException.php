<?php

namespace Sajadsdi\LaraSetting\Exceptions;

class SettingNotFoundException extends \Exception
{
    public function __construct($setting)
    {
        $message = "We can't find '{$setting}' setting from store!";
        parent::__construct($message);
    }
}
