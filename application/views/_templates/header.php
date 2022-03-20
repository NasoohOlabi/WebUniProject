<?php
function pageHeadTag(string $title, array  $options = [])
{

    $language = (isset($_COOKIE['lang'])) ? $_COOKIE['lang'] : 'en';

    if (!isset($_COOKIE['lang'])) {
        setcookie('lang', 'en', 30);
    }
    if ($language === 'ar') {
        require 'application/views/Languages/Arabic.php';
    } elseif ($language === 'en') {
        require 'application/views/Languages/English.php';
    }
    require_once '__header.php';
}
