<?php
require 'AbstractLanguage.php';
class LANGUAGE extends AbstractLanguage
{
    static array $texts =
    [];
    static string $direction = 'ltr';
    static function t($key)
    {
        return $key;
    }
}
